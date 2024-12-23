const prevBtns = document.querySelectorAll(".btn-prev");
const nextBtns = document.querySelectorAll(".btn-next");
const progress = document.getElementById("progress");
const formSteps = document.querySelectorAll(".form-step");
const progressSteps = document.querySelectorAll(".progress-step");
const form = document.querySelector(".form");

let step = 0;

window.addEventListener("load", () => {
    step = 0;
    updateFormSteps();
    updateProgressbar();
    form.reset();
});

// Handle Next Button Click
nextBtns.forEach(btn => {
    btn.addEventListener("click", (e) => {
        const currentFormStep = formSteps[step];
        const requiredFields = currentFormStep.querySelectorAll("input[required], select[required], textarea[required]");
        let allValid = true;

        requiredFields.forEach(field => {
            if (!field.checkValidity()) {
                allValid = false;
                const parent = field.parentElement;
                const errorMsg = parent.querySelector(".error");

                if (errorMsg) {
                    if (field.validity.valueMissing) {
                        errorMsg.innerHTML = "This field is required.";
                    }
                }
            }
        });

        if (step === 0) {
            allValid = allValid && validStep0();
        } else if (step === 1) {
            allValid = allValid && validStep1();
        } else if (step === 2) {
            allValid = allValid && validStep2();
        }

        if (allValid) {
            step++;
            updateFormSteps();
            updateProgressbar();
        } else {
            e.preventDefault();
        }
    });
});

// Handle Previous Button Click
prevBtns.forEach(btn => {
    btn.addEventListener("click", () => {
        step--;
        updateFormSteps();
        updateProgressbar();
    });
});

progressSteps.forEach((progressStep, index) => {
    progressStep.addEventListener("click", () => {
        if (index <= step) {
            // Always allow navigating back
            step = index;
            updateFormSteps();
            updateProgressbar();
        } else {
            // Validate current step before allowing forward navigation
            const currentFormStep = formSteps[step];
            const requiredFields = currentFormStep.querySelectorAll("input[required], select[required], textarea[required]");
            let allValid = true;

            requiredFields.forEach(field => {
                if (!field.checkValidity()) {
                    allValid = false;
                }
            });

            if (allValid) {
                step = index;
                updateFormSteps();
                updateProgressbar();
            }
        }
    });
});

// Update Form Step Visibility
function updateFormSteps() {
    formSteps.forEach((formStep, index) => {
        formStep.classList.toggle("form-step-active", index === step);
    });
}

// Update Progress Bar
function updateProgressbar() {
    progressSteps.forEach((progressStep, idx) => {
        if (idx <= step) {
            progressStep.classList.add("progress-step-active");
        } else {
            progressStep.classList.remove("progress-step-active");
        }
    });

    const progressActive = document.querySelectorAll(".progress-step-active");
    progress.style.width = ((progressActive.length - 1) / (progressSteps.length - 1)) * 100 + "%";
}

// Handle Close Button
document.querySelectorAll('.redirect-index').forEach(button => {
    button.addEventListener('click', () => {
        button.parentElement.style.display = 'none';
        window.location.href = '/index.php';
    });
});


function togglePassword(inputId, toggleIcon) {
    const input = document.getElementById(inputId);
    if (input.type === "password") {
        input.type = "text";
        toggleIcon.innerHTML = "&#x1F441;";
    } else {
        input.type = "password";
        toggleIcon.innerHTML = "<span class='crossed'>&#x1F441;</span>";
    }
}
