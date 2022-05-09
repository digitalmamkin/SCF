## Install WordPress
* You can use **https://wordpress.org/support/article/how-to-install-wordpress/** and skip next steps
1. Download WordPress **https://wordpress.org/latest.zip** and unpack downloaded file
2. Copy sample-config file. Run **cp wp-config-sample.php wp-config.php**
3. Edit the config file
4. Install wp-cli **https://make.wordpress.org/cli/handbook/guides/installing/**
5. Run **wp core install --url=http://127.0.0.1:8880/ --title="WP-PT" --admin_user=root --admin_email=admin@mail.com and save admin password**
6. Run **wp server**
7. Open **http://127.0.0.1:8880/wp-admin** and Enjoy!

## Plugin install
1. Login to WP admin panel
2. Select **Plugins**
3. Select **Add new**
4. Select **Upload plugin**
5. Select plugin zip file in dialog (/release/SCF.zip)
6. Press **Install now** 
7. Press **Activate plugin**
8. Use [scf_short] - short code in WP pages;


https://user-images.githubusercontent.com/11070693/167322645-4d8176c3-6c25-4a97-9ade-c2b973158d93.mov
