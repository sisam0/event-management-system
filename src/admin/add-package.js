document.querySelectorAll(".packageType").forEach((radio) => {
    radio.addEventListener("change", () => {     
        const packageType = document.querySelector('input[name="type"]:checked').value;

        if(packageType === "catering"){
            document.getElementById("displayMenu").style.display = "table-row";
            document.getElementById("displayHall").style.display = "none";

        }
        else if(packageType === "hall"){
            document.getElementById("displayHall").style.display = "table-row";
            document.getElementById("displayMenu").style.display = "none";
        }
        else{
            document.getElementById("displayHall").style.display = "table-row";
            document.getElementById("displayMenu").style.display = "table-row";    
        }
    });
});


const packageForm = document.getElementById('packageForm');
const submitBtn = packageForm.querySelector('input[name="submit"]');

function getErrors() {
    const errors = [];

    const name = packageForm.p_name.value.trim();
    const description = packageForm.description.value.trim();
    const price = packageForm.price.value.trim();
    const type = packageForm.querySelector('input[name="type"]:checked');
    const hall = packageForm.querySelector('input[name="hall_id"]:checked');
    const menus = packageForm.querySelectorAll('input[name="menu_type[]"]:checked');
    const details = packageForm.querySelectorAll('input[name="details[]"]:checked');

    if (name === '') errors.push('Package name cannot be empty.');

    if (!type) {
        errors.push('Select what the package is related to.');
    } else {
        if ((type.value === 'hall' || type.value === 'both') && !hall) {
            errors.push('Select a hall.');
        }
        if ((type.value === 'catering' || type.value === 'both') && menus.length === 0) {
            errors.push('Select at least one catering type.');
        }
    }

    if (description === '') errors.push('Description cannot be empty.');
    if (details.length === 0) errors.push('Select at least one feature.');

    if (price === '' || isNaN(price) || parseFloat(price) <= 0) {
        errors.push('Enter a valid price greater than 0.');
    }

    return errors;
}

function updateSubmitState() {
    const valid = getErrors().length === 0;
    submitBtn.disabled = !valid;
    submitBtn.title = valid ? '' : 'Fill in all fields to enable';
}

// 'input' catches typing; 'change' catches radios/checkboxes. Both bubble to the form.
packageForm.addEventListener('input', updateSubmitState);
packageForm.addEventListener('change', updateSubmitState);

// Run once on page load so the button starts disabled
updateSubmitState();

// Safety net (e.g. if someone re-enables the button in dev tools)
packageForm.addEventListener('submit', function (e) {
    const errors = getErrors();
    if (errors.length > 0) {
        e.preventDefault();
        Swal.fire({
            title: 'Please fix the following',
            html: errors.map(msg => `• ${msg}`).join('<br>'),
            icon: 'warning'
        });
    }
});

function closeFullView() {
    window.location.href = "http://localhost:8081/admin/packages.php";
}