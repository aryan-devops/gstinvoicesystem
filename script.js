document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('invoice-form');
    form.addEventListener('submit', function (e) {
        e.preventDefault();

        // You can add code here to handle the form submission and generate invoices.
        // For a futuristic invoice generator, you'd typically send data to a server or perform more complex logic.
        // This example is just a basic template.

        alert('Invoice created!');
        form.reset();
    });
});
