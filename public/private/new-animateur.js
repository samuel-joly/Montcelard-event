document.addEventListener("DOMContentLoaded", (event) => {
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
);
