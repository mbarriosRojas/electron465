function currency(value, decimals, separators) {

    decimals = decimals >= 0 ? parseInt(decimals, 0) : 2;
    separators = separators || ['.', "'", ','];
    var number = (parseFloat(value) || 0).toFixed(decimals);
    if (number.length <= (4 + decimals))
        return number.replace('.', separators[separators.length - 1]);
    var parts = number.split(/[-.]/);
    value = parts[parts.length > 1 ? parts.length - 2 : 0];
    var result = value.substr(value.length - 3, 3) + (parts.length > 1 ?
        separators[separators.length - 1] + parts[parts.length - 1] : '');
    var start = value.length - 6;
    var idx = 0;
    while (start > -3) {
        result = (start > 0 ? value.substr(start, 3) : value.substr(0, 3 + start))
            + separators[idx] + result;
        idx = (++idx) % 2;
        start -= 3;
    }
    return (parts.length == 3 ? '-' : '') + result;
}

function formatoNumero(num,prefix)
{
num = Math.round(parseFloat(num)*Math.pow(10,2))/Math.pow(10,2)
prefix = prefix || '';
num += '';
var splitStr = num.split('.');
var splitLeft = splitStr[0];
var splitRight = splitStr.length > 1 ? '.' + splitStr[1] : '.00';
splitRight = splitRight + '00';
splitRight = splitRight.substr(0,3);
var regx = /(\d+)(\d{3})/;
while (regx.test(splitLeft)) {
splitLeft = splitLeft.replace(regx, '$1' + ',' + '$2');
}
return prefix + splitLeft + splitRight;
} 

function hola(){
	return 1;
}
