<?php 
include('../includes/header.php');
?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Cashiering</h1>
                </div>
                <div class="col-sm-6">
                    <div class="breadcrumb float-sm-right" id="real-time-clock"></div>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-6">
                    <div class="card card-outline card-secondary">
                        <div class="card-header">
                            Product List
                        </div>
                        <div class="card-body">
                            <table id="example" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Stocks Left</th>
                                        <th>Actions</th>
                                        <th>Price</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        $sql = "SELECT * FROM tbl_product 
                                                INNER JOIN tbl_stocks ON tbl_product.prod_id = tbl_stocks.prod_id
                                                WHERE tbl_stocks.quantity > 0;"; // Adjust the condition as per your requirement
                                        
                                        $result = $conn->query($sql);
                                        
                                        if ($result->num_rows > 0) {
                                            while ($row = $result->fetch_assoc()) { ?>
                                                <tr>
                                                    <td><?php echo $row['prod_name'];?></td>
                                                    <td data-initial-stock="<?php echo $row['quantity']; ?>" id="stock-display-<?php echo $row['prod_id']; ?>"><?php echo $row['quantity']; ?></td>
                                                    <td>
                                                        <button type="button" class="btn btn-info btn-sm" onclick="addToCart('<?php echo $row['prod_id']; ?>', '<?php echo $row['prod_name']; ?>', '<?php echo $row['price']; ?>')">Add to Cart</button>
                                                    </td>
                                                    <td><?php echo $row['price'];?></td>
                                                </tr>
                                            <?php }
                                        }
                                        $conn->close();
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="card card-outline card-danger">
                        <div class="card-header">Items<span id="transaction-number" style="float: right;"></span></div>
                        <div class="card-body" id="selected-products">
                            <!-- Display each selected product in a table -->
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Selected Products</th>
                                        <th>Quantity</th>
                                        <th>Sub Total</th>
                                        <th>VAT (12%)</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody id="product-list">
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>Total Price:</th>
                                        <th id="total-items"></th>
                                        <th id="total-price-footer">0</th>
                                    </tr>
                                </tfoot>
                            </table>
                            <br>
                            <div id="checkout-button-container">
                                <button type="button" class="btn btn-block btn-success" onclick="checkout()">Checkout</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<?php include('../includes/footer_s.php')?>

<script>

    function fetchLatestTransactionNumber() {
        $.ajax({
            url: 'get-latest-transaction.php', // Adjust this URL to your PHP file fetching the latest transaction number
            type: 'GET',
            success: function(response) {
                document.getElementById('transaction-number').innerText = ` Transaction #: ${response}`;
            },
            error: function(xhr, status, error) {
                console.error('Error fetching transaction number:', error);
                // Handle error
            }
        });
    }

    // Call the function to fetch and display the latest transaction number
    fetchLatestTransactionNumber();

    const selectedProducts = [];
    const updatedStock = {};
    function addToCart(id, productName, price) {
        const existingProduct = selectedProducts.find(product => product.name === productName);

        if (existingProduct) {
            existingProduct.quantity += 1;
            existingProduct.total_price = existingProduct.quantity * existingProduct.price;
        } else {
            const subtotal = 1 * price;
            selectedProducts.push({
                prod_id: id,
                name: productName,
                quantity: 1,
                price: price,
                total_price: subtotal
            });

            // Check if the stock has already been updated for this product
            if (!updatedStock[id]) {
                updateStockDisplay(id);
                updatedStock[id] = true;
            }
        }
        if (!validateStock(id)) {
            alert("Not enough stock available.");
            return;
        }
        updateSelectedProducts();
        showCheckoutButton();
    }

    function validateStock(productId) {
        const stockDisplayElement = document.getElementById('stock-display-' + productId);
        const initialStock = parseInt(stockDisplayElement.dataset.initialStock);
        const selectedProduct = selectedProducts.find(product => product.prod_id === productId);
        if (selectedProduct && selectedProduct.quantity > initialStock) {
            return false;
        }
        return true;
    }

    function updateStockDisplay(productId) {
        const stockDisplayElement = document.getElementById('stock-display-' + productId);
        const initialStock = parseInt(stockDisplayElement.dataset.initialStock);
        stockDisplayElement.textContent = initialStock - 1;
    }
    
    function updateSelectedProducts() {
        const productListDiv = document.getElementById('product-list');
        productListDiv.innerHTML = '';
        
        let totalPrice = 0;
        let totalItems = 0;
        let totalTax = 0; // Initialize total tax
        
        const taxRate = 0.12; // 12% tax rate
        
        selectedProducts.forEach(product => {
            const row = document.createElement('tr');
            const nameCell = document.createElement('td');
            nameCell.textContent = product.name;
            
            // Create an input field for quantity
            const quantityCell = document.createElement('td');
            const quantityInput = document.createElement('input');
            quantityInput.type = 'number';
            quantityInput.value = product.quantity;
            quantityInput.addEventListener('focus', () => {
                quantityInput.select(); // Select text inside input field on focus
            });
            quantityInput.addEventListener('blur', () => {
                const newQuantity = parseInt(quantityInput.value);
                if (!isNaN(newQuantity) && newQuantity > 0) {
                    product.quantity = newQuantity;
                    product.total_price = newQuantity * product.price;
                    updateSelectedProducts();
                }
            });
            quantityCell.appendChild(quantityInput);
            
            // Initialize priceCell and totalProductPrice
            const priceCell = document.createElement('td');
            const totalProductPrice = product.price * product.quantity;
            priceCell.textContent = totalProductPrice; // Show total price for this product
            
            // Calculate tax amount for this product
            const taxAmount = totalProductPrice * taxRate;
            totalTax += taxAmount; // Accumulate tax amount
            
            // Add tax amount cell
            const taxCell = document.createElement('td');
            taxCell.textContent = taxAmount.toFixed(2); // Show tax amount with 2 decimal places
        
            // Add actionsCell
            const actionsCell = document.createElement('td');
            const deleteButton = document.createElement('button');
            deleteButton.type = 'button';
            deleteButton.classList.add('btn', 'btn-danger', 'btn-sm');
            deleteButton.textContent = '×';
            deleteButton.onclick = function () {
                removeProduct(product.prod_id);
            };
            actionsCell.appendChild(deleteButton);
    
            // Append name, quantity, price, tax, and actions cells to the row
            row.appendChild(nameCell);
            row.appendChild(quantityCell);
            row.appendChild(priceCell);
            row.appendChild(taxCell);
            row.appendChild(actionsCell);
    
            // Append the row to the product list
            productListDiv.appendChild(row);
    
            // Update total items and total price
            totalItems += product.quantity;
            totalPrice += totalProductPrice;
        });
    
        // Add total tax to the total price
        totalPrice += totalTax;
    
        // Update total price and total items display
        const totalPriceFooterDisplay = document.getElementById('total-price-footer');
        totalPriceFooterDisplay.textContent = totalPrice.toFixed(2); // Show total price with 2 decimal places
        const totalItemsDisplay = document.getElementById('total-items');
        totalItemsDisplay.textContent = totalItems;
        
        // Display total tax alongside total price
        const totalTaxDisplay = document.getElementById('total-tax');
        totalTaxDisplay.textContent = totalTax.toFixed(2); // Show total tax with 2 decimal places
    }
    
    function removeProduct(productId) {
        const productIndex = selectedProducts.findIndex(product => product.prod_id === productId);
        if (productIndex !== -1) {
            selectedProducts.splice(productIndex, 1);
            updatedStock[productId] = false;
        }
        updateStockDisplay(productId)
        updateSelectedProducts();
        showCheckoutButton();
    }
    
    function showCheckoutButton() {
        const checkoutButtonDiv = document.getElementById('checkout-button-container');
        checkoutButtonDiv.style.display = 'block'; // Show the checkout button
    }

    // Function to show the confirmation modal
    function checkout() {
        // Validate if there are enough stocks before proceeding with checkout
        for (const product of selectedProducts) {
            if (!validateStock(product.prod_id)) {
                alert("Not enough stock available for one or more selected products.");
                return;
            }
        }

        // Display confirmation dialog
        const confirmation = confirm("Press 'OK' if you want to Checkout");

        // Check user's response
        if (confirmation) {
            // User clicked "OK", proceed with checkout
            proceedWithCheckout();
        } else {
            // User clicked "Cancel", do nothing
        }
    }
    
    function proceedWithCheckout() {
        // Check if there are selected products in the global array
        if (selectedProducts.length === 0) {
            console.log('No products selected for checkout');
            return;
        }
    
        // Log the selected products before sending
        console.log('Selected Products:', selectedProducts);
    
        // Get the transaction number
        const transactionNumber = document.getElementById('transaction-number').innerText.split(': ')[1];
    
        // Generate receipt content
        const receiptContent = constructReceiptContent();
    
        // Open a new window for printing
        const printWindow = window.open('', '_blank');
    
        // Write the receipt content to the new window
        printWindow.document.write(receiptContent);
    
        // Trigger the print dialog
        printWindow.print();
    
        // Send data to PHP script using AJAX
        $.ajax({
            url: 'checkout-sales.php',
            type: 'POST',
            contentType: 'application/json',
            data: JSON.stringify({ transactionNumber: transactionNumber, selectedProducts: selectedProducts }),
            success: function(response) {
                console.log('Transaction successfully inserted');
                location.reload();
                // Handle success response
            },
            error: function(xhr, status, error) {
                console.error('Error inserting transaction:', error);
                // Handle error
            }
        });
    }
    
    function getSelectedProducts() {
        // Implement logic to fetch selected products, such as from a form
        // For example, if you have checkboxes with products:
        const selectedProductCheckboxes = document.querySelectorAll('.product-checkbox:checked');
        const selectedProducts = [];

        selectedProductCheckboxes.forEach(checkbox => {
            const productId = checkbox.dataset.productId;
            const productName = checkbox.dataset.productName;
            const productQuantity = parseInt(checkbox.value); // Assuming the quantity is in the checkbox value
            const productPrice = parseFloat(checkbox.dataset.productPrice); // Assuming the price is in a data attribute

            selectedProducts.push({
                prod_id: productId,
                name: productName,
                quantity: productQuantity,
                price: productPrice
            });
        });

        return selectedProducts;
    }

    function updateClock() {
        const now = new Date();
        const hours = now.getHours();
        const amPm = hours >= 12 ? 'PM' : 'AM';
        const twelveHourFormat = hours % 12 || 12;
        const minutes = now.getMinutes().toString().padStart(2, '0');
        const seconds = now.getSeconds().toString().padStart(2, '0');
        const month = (now.getMonth() + 1).toString().padStart(2, '0');
        const day = now.getDate().toString().padStart(2, '0');
        const year = now.getFullYear();

        const timeString = `${twelveHourFormat}:${minutes}:${seconds} ${amPm}`;
        const dateString = `${month}/${day}/${year}`;
        document.getElementById('real-time-clock').innerText = `${timeString} - ${dateString}`;
    }

    // Update the clock every second (1000 milliseconds)
    setInterval(updateClock, 1000);

    // Initial call to display the time immediately
    updateClock();
    
    function constructReceiptContent() {
    // Get the transaction number
    const transactionNumber = document.getElementById('transaction-number').innerText.split(': ')[1];

    let receiptContent = `<div class="receipt">
                            <h1>Receipt #${transactionNumber}</h1>
                            <p>Company Name: Orange Graphic Material</p>
                            <p>Date: ${document.getElementById('real-time-clock').innerText}</p>
                            <table>
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th>Quantity</th>
                                        <th>Price</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>`;

    selectedProducts.forEach(product => {
        // Ensure that the price is a number before calling toFixed()
        const price = typeof product.price === 'number' ? product.price : parseFloat(product.price);

        receiptContent += `<tr>
                                <td>${product.name}</td>
                                <td>${product.quantity}</td>
                                <td>₱${price.toFixed(2)}</td> <!-- Use toFixed() after ensuring price is a number -->
                                <td>₱${product.total_price.toFixed(2)}</td>
                            </tr>`;
    });

    receiptContent += `</tbody>
                        </table>
                        <p>Total Price: ₱${document.getElementById('total-price-footer').innerText}</p>
                        <p>VAT (12%): ₱${calculateVAT().toFixed(2)}</p>
                    </div>`;

    return receiptContent;
}

    
    function calculateVAT() {
        const taxRate = 0.12; // 12% tax rate
        let totalTax = 0;
    
        selectedProducts.forEach(product => {
            const totalProductPrice = product.price * product.quantity;
            totalTax += totalProductPrice * taxRate;
        });
    
        return totalTax;
    }
</script>
