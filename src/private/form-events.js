document.addEventListener("DOMContentLoaded", (e) => {
    form_step();
    form_step_click();
    form_2();
    configuration_anim();
    add_anim();
});

function add_anim() {
    let anim_cnt = 2;
    document.querySelector("#add-animateur").addEventListener("click", (e) => {
        e.preventDefault();
        first_anim = document.querySelectorAll("#anim-1")[0];  
        anim = first_anim.cloneNode(false);
        anim.value = "";
        anim.name = "anim-"+anim_cnt;
        anim.id = "anim-"+anim_cnt;
        anim.val = "";
        del_anim = document.querySelector("#del-btn").cloneNode(false);
        del_anim.className = "bg-primary rounded-md text-white m-[0.15em_0.25em_0.25em_0.25em] w-[2.5rem] h-[2.5rem] duration-150 hover:bg-secondary pt-1";
        del_anim.attributes["anim"] = anim_cnt;
        del_anim.id = "del-anim-"+anim_cnt;
        del_anim.innerHTML="-";
        let divNode = first_anim.parentNode.cloneNode(false);
        divNode.append(anim);
        divNode.append(del_anim);
        first_anim.parentNode.parentNode.append(divNode);

        del_anim.addEventListener("click", (e) => {
            e.preventDefault();
            document.querySelector("#anim-"+e.target.attributes["anim"]).parentNode.remove();
        });
    anim_cnt += 1;
    });

}

function configuration_anim() {
    for (let input of document.querySelectorAll("input[name=\"configuration\"]")) {
        input.addEventListener("change", (e) => {
            document.querySelectorAll("input[name=\"configuration\"]").forEach((elem) => {
                elem.parentNode.children[0].classList.remove("bg-focus-blue");
                elem.parentNode.children[0].classList.remove("text-white");
            });
            let label = e.target.parentNode.children[0];
            label.classList.add("bg-focus-blue");
            label.classList.add("text-white");
        });
    }
}

function form_2() {
    form_date();
    show_calendar();
    form_2_calendar_autofill();
    step_2_hide_pause_day()
}

function step_2_hide_pause_day() {
    let checkboxes = document.querySelectorAll("#time-table input[type=\"checkbox\"]");
    checkboxes.forEach((elem) => {
        elem.addEventListener("change", (e) => {
            let mainBox = e.target.parentNode.parentNode.parentNode;
            if (mainBox.classList.contains("line-through") ){
                mainBox.classList.remove("line-through");
                mainBox.querySelectorAll("input").forEach((input) => {
                    input.removeAttribute("disabled");
                    input.classList.remove("bg-gray-300");
                });
            } else {
                mainBox.classList.add("line-through");
                mainBox.querySelectorAll("input").forEach((input) => {
                    input.setAttribute("disabled", true);
                    input.classList.add("bg-gray-300");
                });
                elem.removeAttribute("disabled");
            }
        });
    })
}

function form_step_click() {
    let form_steps = document.querySelector("#resa-steps");
    form_steps.childNodes.forEach((elem) => {
        elem.addEventListener("click", () => {
            let current_step = document.querySelector("#continue-form").getAttribute("current");
            show_form_step(current_step, elem.getAttribute("id").charAt(elem.getAttribute("id").length-1));
        });
    });
}

function show_form_step(current_step, selected_step) {
    let next_btn = document.querySelector("#continue-form");
    let prev_btn = document.querySelector("#prev-form");

    const step_text = document.querySelector("#resa-step-" + current_step + " p");
    step_text.classList.remove("text-white");
    step_text.classList.add("text-inactif");

    const step_text_next = document.querySelector("#resa-step-" + selected_step +" p");
    step_text_next.classList.add("text-white");
    step_text_next.classList.remove("text-inactif");

    const resa_step_mark = document.querySelector("#selected-resa-step-" + current_step);
    resa_step_mark.classList.add("bg-secondary");
    resa_step_mark.classList.remove("bg-ternary");

    const resa_step_next_mark = document.querySelector("#selected-resa-step-" + selected_step);
    resa_step_next_mark.classList.remove("bg-secondary");
    resa_step_next_mark.classList.add("bg-ternary");

    document.querySelector("#form-step-" + next_btn.getAttribute("current")).classList.add("hidden");
    next_btn.setAttribute("current", selected_step);

    if (next_btn.getAttribute("current") != 1) {
        prev_btn.classList.remove("opacity-0");
        prev_btn.setAttribute("last", current_step);
    } else {
        prev_btn.classList.add("opacity-0");
    }

    document.querySelector("#form-step-" + next_btn.getAttribute("current")).classList.remove("hidden");
}

function form_step() {
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

        if (next_btn.getAttribute("current") == 5) {
        } else {
            show_form_step(current_step, parseInt(current_step)+1);
        }
    });

    prev_btn.addEventListener("click", (e) => {
        e.preventDefault();
        const current_step = next_btn.getAttribute("current");
        if (current_step == 1) { return; }
        show_form_step(current_step, parseInt(current_step)-1);
    });
}

function form_date() {
    let start_date = document.querySelector("input[name=\"start-date\"]");
    let end_date = document.querySelector("input[name=\"end-date\"]");
    start_date.setAttribute("min", new Date().toISOString().split("T")[0]);
    start_date.value = new Date().toISOString();

    start_date.addEventListener("change", () => {
        let start_date = document.querySelector("input[name=\"start-date\"]");
        let end_date = document.querySelector("input[name=\"end-date\"]");

        if (end_date.value == "") {
            end_date.setAttribute("min", start_date.value);
            end_date.value = start_date.value;
        }

        let total_time = ((new Date(end_date.value) - new Date(start_date.value)) / (3600 * 24)) / 1000;
        if (total_time != NaN) {
            console.log("not nan");
            show_reserved_days(total_time, start_date);
        } else {
            console.log("NaN", start_date.value);
            end_date.setAttribute("min", start_date.value);
            end_date.value = start_date.value;
        }
    });

    end_date.addEventListener("change", () => {
        let start_date = document.querySelector("input[name=\"start-date\"]");
        let end_date = document.querySelector("input[name=\"end-date\"]");
        /* document.querySelector("#form-step-2").appendChild(time_table); */
        let total_time = ((new Date(end_date.value) - new Date(start_date.value)) / (3600 * 24)) / 1000;
        show_reserved_days(total_time, start_date);
    });
}

function show_reserved_days(total_time, start_date) {
    i = 0;
    j = 4;
    while (total_time < j) {
        document.querySelector("#day-" + j + "-row").classList.add("opacity-0");
        j--;
    }

    while (i < total_time + 1) {
        switch (parseInt(new Date(start_date.value).getDay()) + i) {
            case 0:
                day = "WE";
                break;
            case 1:
                day = "Lundi";
                break;
            case 2:
                day = "Mardi";
                break;
            case 3:
                day = "Mercredi";
                break;
            case 4:
                day = "Jeudi";
                break;
            case 5:
                day = "Vendredi";
                break;
            case 6:
                day = "WE";
                break;
        }

        if (day != "WE") {
            let date = new Date(start_date.value).getDate() + i + "/" + (parseInt(new Date(start_date.value).getMonth()) + 1) + "/2023";
            document.querySelector("#day-" + i + "-row").classList.remove("opacity-0");
            let current_day = document.createElement("p");
            current_day.innerHTML = day;
            let current_date = document.createElement("b");
            current_date.innerHTML = date;

            document.querySelector("#day-" + i).innerHTML = "";
            document.querySelector("#day-" + i).appendChild(current_day);
            document.querySelector("#day-" + i).appendChild(current_date);
        }
        i++;
    }


}

function form_2_calendar_autofill() {
    let start_time = document.querySelector("input[name=\"start-hour\"]");
    let end_time = document.querySelector("input[name=\"end-hour\"]");

    start_time.value = "08:00";
    end_time.value = "18:00";

    let i = 0;
    start_times = [];
    end_times = [];
    participants = [];
    while (i < 5) {
        start_times.push(document.querySelector("input[name=\"day-" + i + "-start-time\"]"));
        end_times.push(document.querySelector("input[name=\"day-" + i + "-end-time\"]"));
        participants.push(document.querySelector("input[name=\"day-" + i + "-number\"]"));
        i++;
    }

    start_time.addEventListener("change", (e) => {
        for (inputs of start_times) {
            inputs.value = e.target.value
        }
    });

    end_time.addEventListener("change", (e) => {
        for (inputs of end_times) {
            inputs.value = e.target.value
        }
    });

    document.querySelector("#participant").addEventListener("change", (e) => {
        for (participant of participants) {
            participant.value = e.target.value;
        }
    });
}

function show_calendar() {
    let calendar = document.querySelector("#show-calendar-btn");
    let time_table = document.querySelector("#time-table");

    calendar.addEventListener("click", (e) => {
        let btn_text = document.querySelector("#show-calendar-btn p");
        const is_open = e.target.getAttribute("opened");
        if (is_open == "true") {
            time_table.classList.add("opacity-0");
            e.target.setAttribute("opened", "false");
            btn_text.innerHTML = "Calendrier ▼";
        } else {
            time_table.classList.remove("opacity-0");
            e.target.setAttribute("opened", "true");
            btn_text.innerHTML = "Calendrier ▲";
        }
    });
}
