
//like in my previous post, I will set the value of window.onload

//equals to init and then define it separately

window.onload = init;

function init(){

    for (var i=0; i < localStorage.length; i++){

        var key = localStorage.key(i);

        if (key.substring(0, 6) === 'sticky'){ //will explain this

            var value = localStorage.getItem(key);

            addStickiesToPage(value); //we will create this one

        }

    }

}

//

//this function will insert the sticky notes to the DOM

//inside the <ul> element we created in the index.html file

function addStickiesToPage(value){

    var stickies = document.getElementById("stickies");

    var sticky = document.createElement("li");

    var span = document.createElement("span");

    span.setAttribute("class", "sticky");

    span.innerHTML = value;

    sticky.appendChild(span);

    stickies.appendChild(sticky);

}


function init(){

    var button = document.getElementById("add_sticky");

    button.onclick = makeSticky; //we have to define this function

    //continue the init function here as shown above by adding

    //the for loop to get items from localStorage

}


function makeSticky(){

    var value = document.getElementById("note").value;

    var key = "sticky_" + localStorage.length;

    localStorage.setItem(key, value);

    addStickiesToPage(value);

}
