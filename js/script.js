function drawBaseCanvas(ctx){
    ctx.fillStyle = '#eeeeee';
    ctx.fillRect(0, 0, 200, 200);
    drawParcelBox(ctx, 10, 190, 80, 40, 6, '#222021');
}

function drawParcelBox(ctx, x, y, cube_width, cube_depth, tape_width, color){
    var cw = cube_width;
    var cwd = cube_depth;
    var tw = tape_width;

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
    ctx.font = '20px Helvetica';
    ctx.fillStyle = color;
    ctx.fillText(text, x, y);
}


window.onload = function () {
    var canvas = document.getElementById("delivery_viz");
    var ctx = canvas.getContext('2d');

    drawBaseCanvas(ctx);
    drawParcelTitle(ctx, 15, 30, 'text', '#8E424B');
};

