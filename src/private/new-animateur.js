document.addEventListener("DOMContentLoaded", (event) => {
    let anim_cnt = 2;
    document.querySelector("#add-animateur").addEventListener("click", (e) => {
        e.preventDefault();
        first_anim = document.querySelectorAll("#anim-1")[0];  
        anim = first_anim.cloneNode(false);
        anim.name = "anim-"+anim_cnt;
        anim.id = "anim-"+anim_cnt;
        anim.val = "";
        del_anim = document.querySelector("#del-btn").cloneNode(false);
        del_anim.className = "rounded-md text-center text-white bg-primary duration-150 hover:bg-secondary min-w-[1.75rem] h-[1.75rem] col-span-1 anim-";
        del_anim.attributes["anim"] = anim_cnt;
        del_anim.id = "del-anim-"+anim_cnt;
        del_anim.innerHTML="X";
        first_anim.parentNode.append(anim);
        first_anim.parentNode.append(del_anim);

        del_anim.addEventListener("click", (e) => {
            e.preventDefault();
            document.querySelector("#anim-"+e.target.attributes["anim"]).parentNode.removeChild(document.querySelector("#anim-"+e.target.attributes["anim"]));
            document.querySelector("#del-anim-"+e.target.attributes["anim"]).parentNode.removeChild(document.querySelector("#del-anim-"+e.target.attributes["anim"]));
        });
    anim_cnt += 1;
    });
}
);
