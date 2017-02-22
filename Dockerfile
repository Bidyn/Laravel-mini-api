FROM nginx
ADD ./default.conf  /etc/nginx/conf.d/
RUN apt-get update && \
    apt-get install -y \
    nodejs \
    npm