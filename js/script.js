<div class="container">
    <h2>Add Product</h2>
    <form id="product_form" action="#" method="POST">
        <div class="form-group">
            <label for="sku">SKU:</label>
            <input type="text" id="sku" name="sku" required autocomplete="off">
        </div>
        <div class="form-group">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" required autocomplete="off">
        </div>
        <div class="form-group">
            <label for="price">Price:</label>
            <input type="number" id="price" name="price" step="0.01" min="0" required autocomplete="off">
        </div>
        <div class="form-group">
            <label for="productType">Product Type:</label>
            <select id="productType" name="productType" required autocomplete="off">
                <option value="" disabled selected>Select Product Type</option>
                <option value="DVD">DVD</option>
                <option value="Book">Book</option>
                <option value="Furniture">Furniture</option>
            </select>
        </div>
        <div id="specificAttribute" class="form-group">
            <!-- Product type specific attribute fields will be added here dynamically -->
        </div>
        <div class="form-group">
            <button type="button" id="saveBtn">Save</button>
            <button type="button" id="cancelBtn">Cancel</button>
        </div>
    </form>
    <div id="notification" class="notification"></div> <!-- Notification div -->
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function(){
        $('#productType').change(function(){
            var productType = $(this).val();
            var description = '';
            if (productType === 'DVD') {
                description = 'Please, provide size (in MB)';
                $('#specificAttribute').html('<label for="size">' + description + '</label><br><input type="text" id="size" name="size" placeholder="Size (MB)" required autocomplete="off">');
            } else if (productType === 'Book') {
                description = 'Please, provide weight (in Kg)';
                $('#specificAttribute').html('<label for="weight">' + description + '</label><br><input type="text" id="weight" name="weight" placeholder="Weight (Kg)" required autocomplete="off">');
            } else if (productType === 'Furniture') {
                description = 'Please, provide dimensions (HxWxL in cm)';
                $('#specificAttribute').html('<label for="dimensions">' + description + '</label><br><input type="text" id="dimensions" name="dimensions" placeholder="Dimensions (HxWxL)" required autocomplete="off">');
            }
        });

        $('#saveBtn').click(function(){
            // Check if all fields are filled
            if ($('#sku').val() === '' || $('#name').val() === '' || $('#price').val() === '' || $('#productType').val() === '') {
                showNotification("Please, submit required data");
                return;
            }
            // Check if price is a valid number
            var price = $('#price').val();
            if (isNaN(price) || parseFloat(price) <= 0) {
                showNotification("Please, provide a valid price");
                return;
            }
            // Check if size, weight, and dimensions are valid
            var productType = $('#productType').val();
            if (productType === 'DVD') {
                var size = $('#size').val();
                if (!(/^\d+$/.test(size))) {
                    showNotification("Please, provide the data of indicated type");
                    return;
                }
            } else if (productType === 'Book') {
                var weight = $('#weight').val();
                if (!(/^\d+$/.test(weight))) {
                    showNotification("Please, provide the data of indicated type");
                    return;
                }
            } else if (productType === 'Furniture') {
                var dimensions = $('#dimensions').val();
                if (!(/^\d+$/.test(dimensions))) {
                    showNotification("Please, provide the data of indicated type");
                    return;
                }
            }
            // Submit the form
            $('#product_form').submit();
        });

        $('#cancelBtn').click(function(){
            window.location.href = 'index.php';
        });

        // Function to show notification
        function showNotification(message) {
            $('#notification').removeClass().addClass('notification');
            $('#notification').text(message);
        }
    });
</script>

