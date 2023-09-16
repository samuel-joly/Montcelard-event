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
        if (next_btn.getAttribute("current") == 5 ) {

        } else {
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
        document.querySelector("#form-step-"+next_btn.getAttribute("current")).classList.add("hidden");
        document.querySelector("#form-step-"+prev_btn.getAttribute("last")).classList.remove("hidden");
        next_btn.setAttribute("current", parseInt(next_btn.getAttribute("current"))-1);
        prev_btn.setAttribute("last", parseInt(prev_btn.getAttribute("last"))-1);
        if (next_btn.getAttribute("current") == 1) {
            prev_btn.classList.add("hidden");
        }
    });

});
