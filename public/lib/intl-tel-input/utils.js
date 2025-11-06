// International Telephone Input v21.1.1
// https://github.com/jackocnr/intl-tel-input.git
window.intlTelInputUtils = {
    numberFormat: {
        E164: 0,         // +33612345678
        INTERNATIONAL: 1, // +33 6 12 34 56 78
        NATIONAL: 2,     // 06 12 34 56 78
        RFC3966: 3       // tel:+33-6-12-34-56-78
    },
    numberType: {
        FIXED_LINE: 0,
        MOBILE: 1,
        FIXED_LINE_OR_MOBILE: 2,
        TOLL_FREE: 3,
        PREMIUM_RATE: 4,
        SHARED_COST: 5,
        VOIP: 6,
        PERSONAL_NUMBER: 7,
        PAGER: 8,
        UAN: 9,
        VOICEMAIL: 10,
        UNKNOWN: -1
    },
    validationError: {
        IS_POSSIBLE: 0,
        INVALID_COUNTRY_CODE: 1,
        TOO_SHORT: 2,
        TOO_LONG: 3,
        IS_POSSIBLE_LOCAL_ONLY: 4,
        INVALID_LENGTH: 5
    },

    formatNumber: function(number, countryCode, format) {
        try {
            // Nettoyage du numéro
            let cleanNumber = number.toString().replace(/[^\d+]/g, '');
            
            // Gestion spéciale pour la France
            if (countryCode === 'fr') {
                // Conversion en format international
                if (cleanNumber.startsWith('00')) {
                    cleanNumber = '+' + cleanNumber.substr(2);
                } else if (cleanNumber.startsWith('0')) {
                    cleanNumber = '+33' + cleanNumber.substr(1);
                } else if (!cleanNumber.startsWith('+')) {
                    cleanNumber = '+33' + cleanNumber;
                }

                // Formatage selon le format demandé
                switch (format) {
                    case this.numberFormat.E164:
                        return cleanNumber;
                    case this.numberFormat.INTERNATIONAL:
                        return cleanNumber.replace(/(\+\d{2})(\d{1})(\d{2})(\d{2})(\d{2})(\d{2})/, '$1 $2 $3 $4 $5 $6');
                    case this.numberFormat.NATIONAL:
                        return '0' + cleanNumber.substr(3).replace(/(\d{1})(\d{2})(\d{2})(\d{2})(\d{2})/, '$1 $2 $3 $4 $5');
                    case this.numberFormat.RFC3966:
                        return 'tel:' + cleanNumber.replace(/(\+\d{2})(\d{1})(\d{2})(\d{2})(\d{2})(\d{2})/, '$1-$2-$3-$4-$5-$6');
                }
            }
            return cleanNumber;
        } catch (e) {
            return number;
        }
    },

    isValidNumber: function(number, countryCode) {
        try {
            // Nettoyage du numéro
            let cleanNumber = number.toString().replace(/[^\d+]/g, '');
            
            // Validation spécifique pour la France
            if (countryCode === 'fr') {
                // Conversion en format international
                if (cleanNumber.startsWith('00')) {
                    cleanNumber = cleanNumber.substr(2);
                } else if (cleanNumber.startsWith('0')) {
                    cleanNumber = cleanNumber.substr(1);
                } else if (cleanNumber.startsWith('+33')) {
                    cleanNumber = cleanNumber.substr(3);
                }

                // Vérification de la longueur et du préfixe pour les mobiles français
                return /^[67]\d{8}$/.test(cleanNumber);
            }
            
            // Validation générique pour les autres pays
            return cleanNumber.length >= 8 && cleanNumber.length <= 15;
        } catch (e) {
            return false;
        }
    },

    getValidationError: function(number, countryCode) {
        try {
            let cleanNumber = number.toString().replace(/[^\d+]/g, '');
            
            if (!cleanNumber) {
                return this.validationError.INVALID_LENGTH;
            }

            if (countryCode === 'fr') {
                // Conversion en format national pour la France
                if (cleanNumber.startsWith('00')) {
                    cleanNumber = cleanNumber.substr(2);
                } else if (cleanNumber.startsWith('+33')) {
                    cleanNumber = cleanNumber.substr(3);
                } else if (!cleanNumber.startsWith('0')) {
                    cleanNumber = '0' + cleanNumber;
                }

                if (!/^0[67]\d{8}$/.test(cleanNumber)) {
                    if (cleanNumber.length < 10) {
                        return this.validationError.TOO_SHORT;
                    }
                    if (cleanNumber.length > 10) {
                        return this.validationError.TOO_LONG;
                    }
                    return this.validationError.INVALID_COUNTRY_CODE;
                }
            }

            return this.validationError.IS_POSSIBLE;
        } catch (e) {
            return this.validationError.INVALID_LENGTH;
        }
    },

    getNumberType: function(number, countryCode) {
        try {
            if (!this.isValidNumber(number, countryCode)) {
                return this.numberType.UNKNOWN;
            }

            let cleanNumber = number.toString().replace(/[^\d+]/g, '');
            
            // Pour la France
            if (countryCode === 'fr') {
                if (cleanNumber.startsWith('00')) {
                    cleanNumber = cleanNumber.substr(2);
                } else if (cleanNumber.startsWith('+33')) {
                    cleanNumber = cleanNumber.substr(3);
                } else if (cleanNumber.startsWith('0')) {
                    cleanNumber = cleanNumber.substr(1);
                }

                // Les numéros commençant par 6 ou 7 sont des mobiles en France
                if (/^[67]/.test(cleanNumber)) {
                    return this.numberType.MOBILE;
                }
                return this.numberType.FIXED_LINE;
            }

            return this.numberType.UNKNOWN;
        } catch (e) {
            return this.numberType.UNKNOWN;
        }
    },

    getExampleNumber: function(countryCode, format) {
        // Exemple pour la France
        if (countryCode === 'fr') {
            const number = '+33612345678';
            return this.formatNumber(number, 'fr', format || this.numberFormat.INTERNATIONAL);
        }
        return '';
    }
};