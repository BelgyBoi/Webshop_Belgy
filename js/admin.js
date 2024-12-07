document.addEventListener('DOMContentLoaded', function() {
    document.querySelector('input[name="product_type"][value="product"]').addEventListener('change', function() {
        document.getElementById('variant-section').style.display = 'none';
        document.getElementById('main-image-url-field').style.display = 'block';
        clearVariantFields();
    });

    document.querySelector('input[name="product_type"][value="variant"]').addEventListener('change', function() {
        document.getElementById('variant-section').style.display = 'block';
        document.getElementById('main-image-url-field').style.display = 'none';
    });

    document.getElementById('original-product-id').addEventListener('change', function() {
        const productId = this.value;
        if (productId) {
            fetchProductDetails(productId);
        } else {
            clearVariantFields();
        }
    });

    function fetchProductDetails(productId) {
        fetch(`classes/get_product_details.php?id=${productId}`)
            .then(response => response.json())
            .then(data => {
                if (data && data.status === 'success') {
                    document.getElementById('name').value = data.product.name;
                    document.getElementById('price').value = data.product.price;
                    document.getElementById('description').value = data.product.description;
                    document.getElementById('category_id').value = data.product.category_id;
                } else {
                    console.error('Error:', data.message);
                }
            })
            .catch(error => console.error('Error fetching product details:', error));
    }

    function clearVariantFields() {
        document.getElementById('name').value = '';
        document.getElementById('price').value = '';
        document.getElementById('description').value = '';
        document.getElementById('category_id').value = '';
    }

    // Clear main image input
    document.getElementById('clear-main-image-btn').addEventListener('click', function() {
        const mainImageInput = document.getElementById('main_image');
        mainImageInput.value = '';
        const previewImage = document.querySelector('#main-image-url-field img');
        if (previewImage) {
            previewImage.remove();
        }
    });

    // Handling additional images dynamically
    function addImageInput() {
        const container = document.getElementById('additional-images-container');
        const wrapper = document.createElement('div');
        wrapper.classList.add('additional-image-wrapper');
        wrapper.innerHTML = `
            <input type="file" name="additional_images[]" class="additional-image">
            <button type="button" class="remove-image-btn custom-remove-image-btn">x</button><br>
        `;
        container.appendChild(wrapper);

        // Add event listener to new input to add another input only when this one is used
        wrapper.querySelector('.additional-image').addEventListener('change', function() {
            const emptyInputs = document.querySelectorAll('.additional-image-wrapper .additional-image');
            if (emptyInputs[emptyInputs.length - 1] === this) {
                addImageInput();
            }
        });
    }

    // Initialize with one image input
    function initializeImageInputs() {
        const container = document.getElementById('additional-images-container');
        container.innerHTML = '<label>Additional Images:</label>';
        addImageInput();
    }

    initializeImageInputs();

    // Removing an image
    document.addEventListener('click', function(event) {
        if (event.target.classList.contains('remove-image-btn')) {
            const wrapper = event.target.parentElement;
            wrapper.remove();

            // Ensure there's always at least one input visible
            const imageInputs = document.querySelectorAll('.additional-image-wrapper');
            if (imageInputs.length === 0) {
                addImageInput();
            }
        }
    });

    // Preview uploaded main image
    document.getElementById('main_image').addEventListener('change', function(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const previewImage = document.createElement('img');
                previewImage.src = e.target.result;
                previewImage.alt = "Image Preview";
                previewImage.style.maxWidth = "200px";
                previewImage.style.marginTop = "10px";
                const mainImageField = document.getElementById('main-image-url-field');
                const existingPreview = mainImageField.querySelector('img');
                if (existingPreview) {
                    existingPreview.remove();
                }
                mainImageField.appendChild(previewImage);
            }
            reader.readAsDataURL(file);
        }
    });

    // List all additional images for the product in the container
    function listAdditionalImages(productImages) {
        const container = document.getElementById('additional-images-container');
        container.innerHTML = '<label>Additional Images:</label>';
        productImages.forEach(image => {
            const wrapper = document.createElement('div');
            wrapper.classList.add('additional-image-wrapper');
            wrapper.innerHTML = `
                <img src="${image.image_url}" alt="Product Image" style="max-width: 100px; max-height: 100px;">
                <button type="button" class="remove-image-btn custom-remove-image-btn">x</button>
            `;
            container.appendChild(wrapper);
        });
        addImageInput();
    }

    // Assuming `productImages` is passed or fetched
    // Example: listAdditionalImages(productImages);

});
