(function(){

    /**
     * Decimal adjustment of a number.
     *
     * @param   {String}    type    The type of adjustment.
     * @param   {Number}    value   The number.
     * @param   {Integer}   exp     The exponent (the 10 logarithm of the adjustment base).
     * @returns {Number}            The adjusted value.
     */
    function decimalAdjust(type, value, exp) {
        // If the exp is undefined or zero...
        if (typeof exp === 'undefined' || +exp === 0) {
            return Math[type](value);
        }
        value = +value;
        exp = +exp;
        // If the value is not a number or the exp is not an integer...
        if (isNaN(value) || !(typeof exp === 'number' && exp % 1 === 0)) {
            return NaN;
        }
        // Shift
        value = value.toString().split('e');
        value = Math[type](+(value[0] + 'e' + (value[1] ? (+value[1] - exp) : -exp)));
        // Shift back
        value = value.toString().split('e');
        return +(value[0] + 'e' + (value[1] ? (+value[1] + exp) : exp));
    }

    // Decimal round
    if (!Math.round10) {
        Math.round10 = function(value, exp) {
            return decimalAdjust('round', value, exp);
        };
    }
    // Decimal floor
    if (!Math.floor10) {
        Math.floor10 = function(value, exp) {
            return decimalAdjust('floor', value, exp);
        };
    }
    // Decimal ceil
    if (!Math.ceil10) {
        Math.ceil10 = function(value, exp) {
            return decimalAdjust('ceil', value, exp);
        };
    }


    // number to string, pluginized from http://stackoverflow.com/questions/5529934/javascript-numbers-to-words
    
    window.num2str = function (num) {
        return window.num2str.convert(num);
    };
    
    window.num2str.ones = ['', 'satu', 'dua', 'tiga', 'empat', 'lima', 'enam', 'tujuh', 'delapan', 'sembilan'];
    window.num2str.tens = ['', 'satu', 'dua', 'tiga', 'empat', 'lima', 'enam', 'tujuh', 'delapan', 'sembilan'];
    window.num2str.teens = ['', 'satu', 'dua', 'tiga', 'empat', 'lima', 'enam', 'tujuh', 'delapan', 'sembilan'];
    
    
    window.num2str.convert_millions = function(num) {
        if (num >= 1000000) {
            return this.convert_millions(Math.floor(num / 1000000)) + " juta " + this.convert_thousands(num % 1000000);
        }
        else {
            return this.convert_thousands(num);
        }
    };
    
    window.num2str.convert_thousands = function(num) {
        if (num >= 1000) {
            return this.convert_hundreds(Math.floor(num / 1000)) + " ribu " + this.convert_hundreds(num % 1000);
        }
        else {
            return this.convert_hundreds(num);
        }
    };
    
    window.num2str.convert_hundreds = function(num) {
        if (num > 99) {
            return this.ones[Math.floor(num / 100)] + " ratus " + this.convert_tens(num % 100);
        }
        else {
            return this.convert_tens(num);
        }
    };
    
    window.num2str.convert_tens = function(num) {
        if (num < 10) return this.ones[num];
        else if (num >= 10 && num < 20) return this.teens[num - 10];
        else {
            return this.tens[Math.floor(num / 10)] + " puluh " + this.ones[num % 10];
        }
    };
    
    window.num2str.convert = function(num) {
        if (num == 0) return "nol";
        else return this.convert_millions(num);
    };

    /**
     * Mengambil parameter url kedalam bentuk Object
     * key = untuk mengembalikan salah satu attribute saja
     * url = url yang ingin di ambil param-nya
     */ 
    window.getQueryParams = function (key, url) {
      // This function is anonymous, is executed immediately and 
      // the return value is assigned to QueryString!
      var query_string = {};
      var query = url ? url.substring(url.indexOf('?') + 1) : window.location.search.substring(1);
      var vars = query.split('&');
      for (var i=0;i<vars.length;i++) {
        var pair = vars[i].split('=');

        /**
         * untuk memperbaiki param dengan format [{"property":"is_sub", "value1":false, "operation":"=" }]
         */
        if(pair.length > 2) {
          pair = [pair.shift(), pair.join('=')]
        }
            // If first entry with this name
        if (typeof query_string[pair[0]] === 'undefined') {
          query_string[pair[0]] = pair[1];
            // If second entry with this name
        } else if (typeof query_string[pair[0]] === 'string') {
          var arr = [ query_string[pair[0]], pair[1] ];
          query_string[pair[0]] = arr;
            // If third or later entry with this name
        } else {
          query_string[pair[0]].push(pair[1]);
        }
      } 
      return key ? query_string[key] : query_string;
  }

  window.initEnv = function() {
    if(getQueryParams('env')) {

      var xmlhttp;
      if (window.XMLHttpRequest){
        // code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp=new XMLHttpRequest();
      } else {
        // code for IE6, IE5
        xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
      }
      xmlhttp.onreadystatechange=function() {
        if (xmlhttp.readyState==4 && xmlhttp.status==200) {
          window.env = eval('('+xmlhttp.responseText+')');
          angular.bootstrap(document, ['minovateApp']);
        }
      }
      var url = getQueryParams('env');
      if(url.indexOf('http') !== 0) {
        url = '.' + url + '-config.json'
      } else {
        url = decodeURIComponent(url);
      }
      xmlhttp.open('GET', url, true);
      xmlhttp.send();
    } else {
      window.env = false;
      angular.bootstrap(document, ['minovateApp']);
    }
  }

  // Application Start Here
  angular.element(document).bind('ready', function(){
    initEnv();
  })
})();
