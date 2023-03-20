FROM nginx


RUN apt update
#RUN mkdir /etc/nginx/sites-available/
#RUN mkdir /etc/nginx/sites-enabled/
#enlace simbolico RUN ln -s /etc/ngcearinx/sites-available/example.com /etc/nginx/sites-enabled/
#COPY nginx.conf /etc/nginx/nginx.conf
#COPY virtual-host/example.com /etc/nginx/sites-available/
#COPY virtual-host/example.com /etc/nginx/sites-enabled/
#COPY virtual-host/example.com /etc/nginx/conf.d/default.conf