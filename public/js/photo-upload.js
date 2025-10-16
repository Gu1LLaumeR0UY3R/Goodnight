/**
 * GlobeNight - Photo Upload with Drag & Drop
 * This script handles drag and drop functionality for photo uploads
 * and displays preview thumbnails of selected images.
 */

(function() {
    'use strict';

    // Initialize drag and drop for all photo upload zones
    function initPhotoUpload() {
        const dropZones = document.querySelectorAll('.photo-drop-zone');
        
        dropZones.forEach(dropZone => {
            const fileInput = dropZone.querySelector('input[type="file"]');
            const previewContainer = dropZone.querySelector('.photo-preview-container');
            const dropText = dropZone.querySelector('.drop-zone-text');
            
            if (!fileInput || !previewContainer) return;

            // Prevent default drag behaviors
            ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                dropZone.addEventListener(eventName, preventDefaults, false);
                document.body.addEventListener(eventName, preventDefaults, false);
            });

            // Highlight drop zone when item is dragged over it
            ['dragenter', 'dragover'].forEach(eventName => {
                dropZone.addEventListener(eventName, () => {
                    dropZone.classList.add('drop-zone-active');
                }, false);
            });

            ['dragleave', 'drop'].forEach(eventName => {
                dropZone.addEventListener(eventName, () => {
                    dropZone.classList.remove('drop-zone-active');
                }, false);
            });

            // Handle dropped files
            dropZone.addEventListener('drop', (e) => {
                const dt = e.dataTransfer;
                const files = dt.files;
                handleFiles(files, fileInput, previewContainer, dropText);
            }, false);

            // Handle file selection via click
            fileInput.addEventListener('change', (e) => {
                const files = e.target.files;
                handleFiles(files, fileInput, previewContainer, dropText);
            });

            // Click on drop zone to open file selector
            dropZone.addEventListener('click', (e) => {
                if (e.target === dropZone || e.target === dropText) {
                    fileInput.click();
                }
            });
        });
    }

    // Prevent default drag behaviors
    function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }

    // Handle files (from drop or file input)
    function handleFiles(files, fileInput, previewContainer, dropText) {
        // Convert FileList to Array
        const filesArray = Array.from(files);
        
        // Filter only image files
        const imageFiles = filesArray.filter(file => file.type.startsWith('image/'));
        
        if (imageFiles.length === 0) {
            alert('Veuillez sélectionner uniquement des fichiers image.');
            return;
        }

        // Get existing files from the input
        const existingFiles = Array.from(fileInput.files);
        
        // Combine existing files with new image files
        const combinedFiles = [...existingFiles, ...imageFiles];

        // Update the file input with the combined files
        const dataTransfer = new DataTransfer();
        combinedFiles.forEach(file => {
            dataTransfer.items.add(file);
        });
        fileInput.files = dataTransfer.files;

        // Clear previous previews and re-render all combined files
        previewContainer.innerHTML = '';

        // Hide drop text when files are selected
        if (dropText && combinedFiles.length > 0) {
            dropText.style.display = 'none';
        }

        // Generate preview for each combined file
        combinedFiles.forEach((file, index) => {
            previewFile(file, previewContainer, index, fileInput, dropText);
        });
    }

    // Preview individual file
    function previewFile(file, previewContainer, index, fileInput, dropText) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            const previewItem = document.createElement('div');
            previewItem.className = 'photo-preview-item';
            previewItem.dataset.index = index;
            
            const img = document.createElement('img');
            img.src = e.target.result;
            img.alt = file.name;
            
            const removeBtn = document.createElement('button');
            removeBtn.type = 'button';
            removeBtn.className = 'photo-remove-btn';
            removeBtn.innerHTML = '&times;';
            removeBtn.title = 'Supprimer cette photo';
            
            removeBtn.addEventListener('click', (event) => {
                event.stopPropagation();
                removePhoto(index, previewContainer, fileInput, dropText);
            });
            
            const fileName = document.createElement('div');
            fileName.className = 'photo-file-name';
            fileName.textContent = file.name;
            
            previewItem.appendChild(img);
            previewItem.appendChild(removeBtn);
            previewItem.appendChild(fileName);
            previewContainer.appendChild(previewItem);
        };
        
        reader.readAsDataURL(file);
    }

    // Remove photo from selection
    function removePhoto(indexToRemove, previewContainer, fileInput, dropText) {
        const dataTransfer = new DataTransfer();
        const files = Array.from(fileInput.files);
        
        files.forEach((file, index) => {
            if (index !== indexToRemove) {
                dataTransfer.items.add(file);
            }
        });
        
        fileInput.files = dataTransfer.files;
        
        // Remove preview item
        const previewItem = previewContainer.querySelector(`[data-index="${indexToRemove}"]`);
        if (previewItem) {
            previewItem.remove();
        }

        // Re-index remaining preview items
        const remainingItems = previewContainer.querySelectorAll('.photo-preview-item');
        remainingItems.forEach((item, newIndex) => {
            item.dataset.index = newIndex;
        });

        // Show drop text if no files remain
        if (fileInput.files.length === 0 && dropText) {
            dropText.style.display = 'block';
        }
    }

    // Initialize on DOM ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initPhotoUpload);
    } else {
        initPhotoUpload();
    }
})();

