document.addEventListener("DOMContentLoaded", () => {
    configuration_anim();
});

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
