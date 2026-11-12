/**
 * Champ téléphone international (intl-tel-input v21).
 *
 *   const iti = initPhoneInput('#tel', '#full_tel');
 *
 * - L'utilisateur saisit son numéro national (06 12 34 56 78 ou 6 12 34 56 78) ;
 *   l'indicatif (+33) est géré uniquement par le sélecteur de pays.
 * - Le champ caché reçoit le numéro au format E.164 (+33612345678), seulement s'il est valide.
 * - Le champ est facultatif : vide = valide.
 * - Les erreurs s'affichent à la sortie du champ ou à l'envoi du formulaire, jamais pendant la frappe.
 */
(function (global) {
  'use strict';

  var MESSAGES = {
    1: "L'indicatif du pays n'est pas valide",
    2: 'Le numéro est trop court',
    3: 'Le numéro est trop long',
    5: "Ce numéro n'a pas une longueur valide"
  };

  function errorNode(input) {
    var holder = input.closest('.iti') || input;
    var node = holder.parentNode.querySelector('.tel-error');
    if (!node) {
      node = document.createElement('p');
      node.className = 'tel-error';
      node.setAttribute('role', 'alert');
      holder.parentNode.insertBefore(node, holder.nextSibling);
    }
    return node;
  }

  function showError(input, message) {
    errorNode(input).textContent = message;
    input.classList.add('error');
    input.setAttribute('aria-invalid', 'true');
  }

  function clearError(input) {
    var holder = input.closest('.iti') || input;
    var node = holder.parentNode.querySelector('.tel-error');
    if (node) node.remove();
    input.classList.remove('error');
    input.removeAttribute('aria-invalid');
  }

  global.initPhoneInput = function (inputSelector, hiddenSelector, options) {
    var input = document.querySelector(inputSelector);
    var hidden = hiddenSelector ? document.querySelector(hiddenSelector) : null;
    if (!input || !global.intlTelInput) return null;

    var iti = global.intlTelInput(input, Object.assign({
      initialCountry: 'fr',
      separateDialCode: true,
      nationalMode: true,
      strictMode: true,        // ignore les caractères invalides et limite la longueur
      formatAsYouType: true,
      formatOnDisplay: true,
      utilsScript: '/lib/intl-tel-input/utils.js'
    }, options || {}));

    var utilsReady = function () { return !!global.intlTelInputUtils; };

    // Met à jour le champ caché. Renvoie false si le numéro saisi est invalide.
    function sync(showErrors) {
      var raw = input.value.trim();
      if (!raw) {
        clearError(input);
        if (hidden) hidden.value = '';
        return true;
      }
      if (!utilsReady()) return true;      // validation impossible pour l'instant : le serveur validera

      if (iti.isValidNumber()) {
        clearError(input);
        if (hidden) hidden.value = iti.getNumber();   // E.164
        return true;
      }
      if (hidden) hidden.value = '';
      if (showErrors) {
        var code = iti.getValidationError();
        showError(input, MESSAGES[code] || 'Numéro de téléphone invalide');
      }
      return false;
    }

    input.addEventListener('input', function () { sync(false); });
    input.addEventListener('countrychange', function () { sync(input.value.trim() !== ''); });
    input.addEventListener('blur', function () {
      if (sync(true) && utilsReady() && input.value.trim() && iti.isValidNumber()) {
        input.value = iti.getNumber(global.intlTelInputUtils.numberFormat.NATIONAL);   // 06 12 34 56 78
      }
    });

    var form = input.closest('form');
    if (form) {
      form.addEventListener('submit', function (e) {
        if (!sync(true)) {
          e.preventDefault();
          input.focus();
        }
      });
    }

    // numéro déjà enregistré (formulaire d'édition) : une fois les règles chargées, on le synchronise
    if (iti.promise && typeof iti.promise.then === 'function') {
      iti.promise.then(function () { sync(false); });
    }

    iti.validateForSubmit = function () { return sync(true); };
    return iti;
  };
})(window);
