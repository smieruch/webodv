#!/bin/bash

cd /home/smieruch/Projects/webODV/webodv/public


#check path ! security
P=`pwd`
echo $P

if [ "$P" == "/home/smieruch/Projects/webODV/webodv/public" ]
   then

       #minification of JS files
       find . -type f \
	    -name "*.js" ! -name "*.min.*" ! -name "app.js" \
	    -exec echo {} \; \
	    -exec uglifyjs -o {}.min {} \; \
	    -exec sh -c 'x="{}.min"; cp "$x"  "${x%%.js.min}".min.js'  \; \
	    -exec rm {} \; \
	    -exec rm {}.min \; 

	     
       #minification of CSS files
       find . -type f \
	    -name "*.css" ! -name "*.min.*" ! -name "app.css" \
	    -exec echo {} \; \
	    -exec uglifycss --output {}.min {} \; \
	    -exec sh -c 'x="{}.min"; cp "$x"  "${x%%.css.min}".min.css'  \; \
	    -exec rm {} \; \
	    -exec rm {}.min \; 

fi
