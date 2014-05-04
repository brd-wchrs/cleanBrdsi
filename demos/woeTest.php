<!DOCTYPE html>
<html>
  <head>
    <!-- 
      File: woeTest.php

      Author: Nicholas St.Pierre
    
      Using someone else's class TwitterAPIExchange.php for OAuth.
      -->
    <title>Testing Yahoo's! WOEID API</title>

    <!-- this uses jQuery to do AJAX stuff -->
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
    <script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>

    <script>

      var countriesObject;

      // haha shhhhhhh
      var appID="GRh.G7bV34FMED5i9XIBCxu8033BT515hgwsTy0M6TGSINtemUJcHP7oe64rqg58xkElnfeCo.BSeAuNulF8h5F8xB9TrvM-";


      function ajaxErr(a, b, c)
      {
        alert("the ajax request failed.");
        console.log("Ajax error...?");
      }


      // thanks http://code.tutsplus.com/tutorials/quick-tip-use-jquery-to-retrieve-data-from-an-xml-file--net-390
      // easy, yes?
      function convertXML(data)
      {
      
        var ret = $("<select></select>"), // this will be populated with <option>'s and returned
            name,  // holds the name  of the country
            value; // holds the WOEID of the country


        // the XML contains a bunch of stuff, all contained in <place> elements
        $(data).find('place').each(function(index, element){

	  name  = $(element).find('name').text(); //
	  value = $(element).find('woeid').text();	
  
	  // there may be a better, more-jquery-savvy way to do this, but this works for now. 
 	  ret.append("<option value=\""+value+"\">"+name+"</option>");

        });

	return ret;

      }


      function DOMinsert(thingToInsert, target)
      {
        $(target).html(thingToInsert);
      }


      function makeCountriesDropDown(data)
      {
        DOMinsert(convertXML(data), "#countries");
      }



      function getCountries()
      {
      
        $.ajax({

          dataType: "xml",
          error:    ajaxErr,
          url:      "http://where.yahooapis.com/v1/countries?appid="+appID,
          success:  function(data)
                    { 
	              countriesObject=data;
                      console.log("ajax success!");
                      makeCountriesDropDown(data);
                    }
      });





      }

    </script>
  </head>
  <body>
    <div>
      <button onclick="getCountries();">
	<p>getCountries()</p>
      </button>
    </div>
    <div id="countries">
      <small>placeholder</small>
    </div>
    <br>
  </body>
</html>
