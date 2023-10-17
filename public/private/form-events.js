document.addEventListener("DOMContentLoaded", (e) => {
    form_step();
    form_date();
});

function form_step(e) {
    let next_btn = document.querySelector("#continue-form");
    let prev_btn = document.querySelector("#prev-form");
    let count = 1;

    if (count == 1) {
        prev_btn.classList.add("opacity-0");
    } else {
        prev_btn.classList.remove("opacity-0");
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
            resa_step_mark.classList.add("bg-secondary");
            resa_step_mark.classList.remove("bg-ternary");
            resa_step_next_mark.classList.remove("bg-secondary");
            resa_step_next_mark.classList.add("bg-ternary");

            document.querySelector("#form-step-"+next_btn.getAttribute("current")).classList.add("hidden");
            next_btn.setAttribute("current", parseInt(next_btn.getAttribute("current"))+1);
            if (next_btn.getAttribute("current") != 1) {
                prev_btn.classList.remove("opacity-0");
                prev_btn.setAttribute("last", next_btn.getAttribute("current")-1);
            }
            document.querySelector("#form-step-"+next_btn.getAttribute("current")).classList.remove("hidden");
        }
    });

    prev_btn.addEventListener("click", (e) => {
        e.preventDefault();
        const current_step = next_btn.getAttribute("current"); 
        if (current_step==1) {return;}
        const step_text_id = "#resa-step-"+current_step;
        const step_text_prev_id = "#resa-step-"+(parseInt(current_step) - 1);
        const resa_step_mark = document.querySelector("#selected-resa-step-"+current_step);
        const resa_step_prev_mark = document.querySelector("#selected-resa-step-"+(parseInt(current_step) - 1));

        document.querySelector(step_text_id).classList.remove("text-white");
        document.querySelector(step_text_id).classList.add("text-inactif");
        document.querySelector(step_text_prev_id).classList.add("text-white");
        document.querySelector(step_text_prev_id).classList.remove("text-inactif");
        resa_step_mark.classList.add("bg-secondary");
        resa_step_mark.classList.remove("bg-ternary");
        resa_step_prev_mark.classList.remove("bg-secondary");
        resa_step_prev_mark.classList.add("bg-ternary");

        document.querySelector("#form-step-"+next_btn.getAttribute("current")).classList.add("hidden");
        document.querySelector("#form-step-"+prev_btn.getAttribute("last")).classList.remove("hidden");
        next_btn.setAttribute("current", parseInt(next_btn.getAttribute("current"))-1);
        prev_btn.setAttribute("last", parseInt(prev_btn.getAttribute("last"))-1);
        if (next_btn.getAttribute("current") == 1) {
            prev_btn.classList.add("opacity-0");
        }
    });
}

function form_date() {
    let start_date = document.querySelector("input[name=\"start-date\"]");
    let end_date = document.querySelector("input[name=\"end-date\"]");
    start_date.setAttribute("min", new Date().toISOString().split("T")[0]);
    start_date.value = new Date().toISOString();
    let time_table = document.querySelector("#time-table").cloneNode(true);
    document.querySelector("#time-table").classList.remove("opacity-0");

    start_date.addEventListener("change", () => {
        let start_date = document.querySelector("input[name=\"start-date\"]");
        let end_date = document.querySelector("input[name=\"end-date\"]");
        if (end_date.getAttribute("disabled") !== null) {
            end_date.removeAttribute("disabled");
        }

        let total_time = ((new Date(end_date.value) - new Date(start_date.value))/(3600*24))/1000;
        if (total_time <= 0 || total_time == NaN) {
            end_date.setAttribute("min", start_date.value);
            end_date.value = start_date.value;
        } else {
            document.querySelector("#time-table").classList.remove("opacity-0");
            show_reserved_days(total_time, start_date);
        }
    });

    end_date.addEventListener("change", () => {
        let start_date = document.querySelector("input[name=\"start-date\"]");
        let end_date = document.querySelector("input[name=\"end-date\"]");
        /* document.querySelector("#form-step-2").appendChild(time_table); */
        let total_time = ((new Date(end_date.value) - new Date(start_date.value))/(3600*24))/1000;
        show_reserved_days(total_time, start_date);
    });
}

function show_reserved_days(total_time, start_date) {
    i = 0;
    j = 4;
    while (total_time < j) {
        document.querySelector("#day-"+j+"-row").classList.add("opacity-0");
        j--;
    }
    
    while (i < total_time+1) {
        switch (parseInt(new Date(start_date.value).getDay()) + i) {
            case 0 :
                day= "WE";
                break;
            case 1 :
                day = "Lundi";
                break;
            case 2 :
                day = "Mardi";
                break;
            case 3 :
                day = "Mercredi";
                break;
            case 4 :
                day = "Jeudi";
                break;
            case 5 :
                day = "Vendredi";
                break;
            case 6 :
                day= "WE";
                break;
        }

        if (day != "WE") { 
            let date =  new Date(start_date.value).getDate() +i+"/"+ (parseInt(new Date(start_date.value).getMonth()) + 1)+"/2023";
            document.querySelector("#day-"+i+"-row").classList.remove("opacity-0");
            let current_day = document.createElement("p");
            current_day.innerHTML = day;
            let current_date = document.createElement("b");
            current_date.innerHTML = date;

            document.querySelector("#day-"+i).innerHTML=""; 
            document.querySelector("#day-"+i).appendChild(current_day);
            document.querySelector("#day-"+i).appendChild(current_date);
        } 
        i++;
    }


}
