document.addEventListener("DOMContentLoaded", (e) => {
    form_step();
    form_date();
    form_hour();
});

function form_step(e) {
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
}

function form_date() {
    let start_date = document.querySelector("input[name=\"start-date\"]");
    start_date.setAttribute("min", new Date().toISOString().split("T")[0]);
    let end_date = document.querySelector("input[name=\"end-date\"]");
    let time_table = document.querySelector("#time-table").cloneNode(true);

    start_date.addEventListener("change", (e) => {
        document.querySelector("#time-table").remove();
        end_date.setAttribute("min", start_date.value);
        end_date.value = start_date.value;
    });

    end_date.addEventListener("change", (e) => {
        document.querySelector("#form-step-2").appendChild(time_table);

        let total_time = ((new Date(end_date.value) - new Date(start_date.value))/(3600*24))/1000;
        document.querySelector("#time-table").classList.remove("hidden");
        let time_table_start_time = document.querySelector("#time-table-start-time");
        let time_table_end_time = document.querySelector("#time-table-end-time");
        let time_table_no_event = document.querySelector("#time-table-no-event");

        let i = 0;
        let day = "";
        while (i < 5) {
            document.querySelector("#day-"+i).classList.add("hidden");
            document.querySelector("#day-"+i+"-start-time").classList.add("hidden");
            document.querySelector("#day-"+i+"-end-time").classList.add("hidden");
            document.querySelector("#day-"+i+"-no-event").classList.add("hidden");
            i++;
        }
        i = 0;
        while (i <= total_time) {
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
                let date =  new Date(start_date.value).getDate() +i+"/"+ (parseInt(new Date(start_date.value).getMonth()) + 1);
                document.querySelector("#day-"+i).classList.remove("hidden");
                document.querySelector("#day-"+i).innerHTML = day+" "+date;
                document.querySelector("#day-"+i+"-start-time").classList.remove("hidden");
                document.querySelector("#day-"+i+"-end-time").classList.remove("hidden");
                document.querySelector("#day-"+i+"-no-event").classList.remove("hidden");
            }
            i++;
        }
    });
}

function form_hour() {
    let start_hour = document.querySelector("input[name=\"start-hour\"]");
    let end_hour = document.querySelector("input[name=\"end-hour\"]");
    start_hour.addEventListener("change", (e) => {
        end_hour.setAttribute("min", start_hour.value);
        end_hour.value = start_hour.value;
        for (let input of document.querySelectorAll("#time-table-start-time div input")) {
            input.value = start_hour.value;
        }
    });
    end_hour.addEventListener("change", (e) => {
        for (let input of document.querySelectorAll("#time-table-end-time div input")) {
            input.value = end_hour.value;
        }
    });
}
