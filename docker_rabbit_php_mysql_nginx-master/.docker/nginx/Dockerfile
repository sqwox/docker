FROM nginx:stable-alpine

RUN apk add --no-cache bash bash-completion;

# Add nginx configuration
COPY conf/default.conf /etc/nginx/conf.d/default.conf
COPY conf/nginx.conf /etc/nginx/nginx.conf
RUN echo 'server_tokens off;' > /etc/nginx/conf.d/server_tokens.conf

# Uncomment for ssl certs
#RUN mkdir /etc/nginx/certs
#COPY conf/ssl /etc/nginx/certs

# Set workdir
WORKDIR /var/www

CMD ["nginx", "-g", "daemon off;"]
