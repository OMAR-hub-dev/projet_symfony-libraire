// +++++Generalites sur les fonctions+++++

//----fonction declarative--
console.log(sum(4,5));
console.log(sum(6,5));
console.log(sum(8,5));

function sum(g , d) {
    return g +d;
}

//log sum(6,3) ;

// -----expresssin de fonction---
let expression = function(a,b){
        return a*b
}
console.log(expression(5,4));

//---- fonction fléchée-----
let divisionFlechee= (x,y) => {
    return x/y
}
console.log(divisionFlechee(15,5))

let entiers = [1, 2, 3, 8];
console.log(entiers.map(
    function(i){
        return i+ 5;
    }))

// ----fonction sur une ligne

let getRandom=() =>{
    return Math.random()
}
console.log(getRandom())
let nom ="omar"
let prenom="amir"

let affiche = (nom, prenom) => console.log(`je m'apelle ${nom} ${prenom}`);
affiche ('paul','jean');


//----fonction qui s'auto excute
(function multiply (a ,b) {
console.log(a*b)})(5,6);
