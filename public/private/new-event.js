document.addEventListener("DOMContentLoaded", (e) => {
    let max_step = 5;
    let form_btn = document.querySelector("#form-btn");
    let next_btn = document.querySelector("#continue-form");
    let prev_btn = document.querySelector("#prev-form");
    let count = 1;

    if (count == 1) {
        prev_btn.classList.add("hidden");
    } else {
        prev_btn.classList.remove("hidden");
    }

    next_btn.addEventListener("click", (e) => {
        e.preventDefault();
        const current_step = next_btn.getAttribute("current"); 
        const step_text_id = "#resa-step-"+current_step;
        const step_text_next_id = "#resa-step-"+(parseInt(current_step) + 1);
        const resa_step_mark = document.querySelector("#selected-resa-step-"+current_step);
        const resa_step_next_mark = document.querySelector("#selected-resa-step-"+(parseInt(current_step) + 1));

        if (next_btn.getAttribute("current") == 5 ) {

        } else {
            document.querySelector(step_text_id).classList.remove("text-white");
            document.querySelector(step_text_id).classList.add("text-inactif");
            document.querySelector(step_text_next_id).classList.add("text-white");
            document.querySelector(step_text_next_id).classList.remove("text-inactif");
            resa_step_mark.classList.add("bg-ternary");
            resa_step_mark.classList.remove("bg-secondary");
            resa_step_next_mark.classList.remove("bg-ternary");
            resa_step_next_mark.classList.add("bg-secondary");

            document.querySelector("#form-step-"+next_btn.getAttribute("current")).classList.add("hidden");
            next_btn.setAttribute("current", parseInt(next_btn.getAttribute("current"))+1);
            if (next_btn.getAttribute("current") != 1) {
                prev_btn.classList.remove("hidden");
                prev_btn.setAttribute("last", next_btn.getAttribute("current")-1);
            }
            document.querySelector("#form-step-"+next_btn.getAttribute("current")).classList.remove("hidden");
        }
    });

    prev_btn.addEventListener("click", (e) => {
        e.preventDefault();
        const current_step = next_btn.getAttribute("current"); 
        const step_text_id = "#resa-step-"+current_step;
        const step_text_prev_id = "#resa-step-"+(parseInt(current_step) - 1);
        const resa_step_mark = document.querySelector("#selected-resa-step-"+current_step);
        const resa_step_prev_mark = document.querySelector("#selected-resa-step-"+(parseInt(current_step) - 1));

        document.querySelector(step_text_id).classList.remove("text-white");
        document.querySelector(step_text_id).classList.add("text-inactif");
        document.querySelector(step_text_prev_id).classList.add("text-white");
        document.querySelector(step_text_prev_id).classList.remove("text-inactif");
        resa_step_mark.classList.add("bg-ternary");
        resa_step_mark.classList.remove("bg-secondary");
        resa_step_prev_mark.classList.remove("bg-ternary");
        resa_step_prev_mark.classList.add("bg-secondary");

        document.querySelector("#form-step-"+next_btn.getAttribute("current")).classList.add("hidden");
        document.querySelector("#form-step-"+prev_btn.getAttribute("last")).classList.remove("hidden");
        next_btn.setAttribute("current", parseInt(next_btn.getAttribute("current"))-1);
        prev_btn.setAttribute("last", parseInt(prev_btn.getAttribute("last"))-1);
        if (next_btn.getAttribute("current") == 1) {
            prev_btn.classList.add("hidden");
        }
    });

});
