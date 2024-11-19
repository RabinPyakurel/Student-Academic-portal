const prevBtns = document.querySelectorAll(".btn-prev");
const nextBtns = document.querySelectorAll(".btn-next");
const progress = document.getElementById("progress");
const formSteps = document.querySelectorAll(".form-step");
const progressSteps = document.querySelectorAll(".progress-step");
const form = document.querySelector(".form")
let step = 0;
window.addEventListener("load", () => {
    step = 0;
    updateFormSteps();
    updateProgressbar();
    form.reset();
});

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
                        errorMsg.innerHTML = require;
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


prevBtns.forEach(btn => {
    btn.addEventListener("click", () => {
        step--;
        updateFormSteps();
        updateProgressbar();
    });
});

function updateFormSteps() {
    formSteps.forEach(formStep => {
        formStep.classList.contains("form-step-active") &&
            formStep.classList.remove("form-step-active");
    });
    formSteps[step].classList.add("form-step-active");
}

function updateProgressbar() {
    progressSteps.forEach((progressStep, idx) => {
        if (idx < step + 1) {
            progressStep.classList.add("progress-step-active");
        } else {
            progressStep.classList.remove("progress-step-active");
        }
    });

    const progressActive = document.querySelectorAll(".progress-step-active");
    progress.style.width = ((progressActive.length - 1) / (progressSteps.length - 1)) * 100 + "%";
}
