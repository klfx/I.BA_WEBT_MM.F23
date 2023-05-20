function drawBaseCanvas(ctx){
    ctx.fillStyle = '#ffffff';
    ctx.fillRect(0, 0, 400, 250);
    drawParcelBox(ctx, 90, 180, 100, 50, 10, '#222021');
}

function drawParcelBox(ctx, x, y, cube_width, cube_depth, tape_width, color){
    let cw = cube_width;
    let cwd = cube_depth;
    let tw = tape_width;

    //print box
    ctx.beginPath();
    ctx.moveTo(x, y);
    ctx.lineTo(x + cw, y);
    ctx.lineTo(x + cw, y - cw);
    ctx.lineTo(x, y - cw);
    ctx.lineTo(x, y);
    ctx.moveTo(x + cw, y);
    ctx.lineTo(x + cw + cwd, y - cwd);
    ctx.lineTo(x + cw + cwd, y - cwd - cw);
    ctx.lineTo(x + cw, y - cw);
    ctx.lineTo(x + cw + cwd, y - cwd - cw);
    ctx.lineTo(x + cwd, y - cwd - cw);
    ctx.lineTo(x, y - cw)
    ctx.strokeStyle = color;
    ctx.stroke();
    ctx.closePath();

    //print tape
    ctx.beginPath();
    ctx.moveTo(x + (cw/2) - (tw), y - cw);
    ctx.lineTo(x + (cw/2) - (tw), y - cw + 30);
    ctx.lineTo(x + (cw/2) + tw, y - cw + 30);
    ctx.lineTo(x + (cw/2) + tw, y - cw);
    ctx.lineTo(x + (cw/2) + tw + cwd, y - cw - cwd);
    ctx.lineTo(x + (cw/2) - tw + cwd, y - cw - cwd);
    ctx.lineTo(x + (cw/2) - tw, y - cw);
    ctx.fillStyle = color;
    ctx.fill();
    ctx.closePath();

    ctx.strokeStyle  = color;
    ctx.stroke();
    ctx.closePath(); 
}

function drawParcelTitle(ctx, x, y, text, color){
    drawBaseCanvas(ctx);
    ctx.font = "20px Helvetica";
    ctx.fillStyle = color;
    ctx.fillText(text, x, y);
}

function formValidation(){
    let delivery_form_error = document.getElementById("delivery_form_error");
    let tracking_nr = document.forms["delivery_form"]["tracking_nr"].value;
    let delivery_option = document.forms["delivery_form"]["delivery_option"].value;
    let consent = document.forms["delivery_form"]["consent"].checked;
    /* alert(tracking_nr + " " + delivery_option + " " + consent); */
    let re_tracking_nr = /^[0-9]{18}$/;

    delivery_form_error.innerHTML = "";
    
    if (!re_tracking_nr.test(tracking_nr)) {
        delivery_form_error.innerHTML += "Bitte geben Sie eine gültige nationale Sendungsnummer der Post an. Bsp. 990012345612345678<br>";
        delivery_form_error.removeAttribute("hidden");
    }

    if (!consent) {
        delivery_form_error.innerHTML += "Bitte akzeptieren Sie die AGB.";
        delivery_form_error.removeAttribute("hidden");
    }

    if (!re_tracking_nr.test(tracking_nr) || !consent) {
        return false;
    }

    return true;
    
}

window.onload = function () {
    let canvas = document.getElementById("delivery_viz");
    let ctx = canvas.getContext('2d');
    let delivery_option = document.querySelector("#delivery_option");
    
    //Draw default canvas
    drawBaseCanvas(ctx);
    drawParcelTitle(ctx,15,220,`✨${delivery_option.options[0].text}✨`,'#222021');

    delivery_option.addEventListener("change", (event) => {
     drawParcelTitle(ctx,15,220,`✨${delivery_option.options[event.target.value-1].text}✨`,'#222021')
    });
}
