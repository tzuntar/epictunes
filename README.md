<p align="center">
  <a href="" rel="noopener">
 <img width=200px height=200px src="./assets/img/logo.svg" alt="EpicTunes Logo"></a>
</p>

<h3 align="center">EpicTunes</h3>

## 🎵 About <a name = "about"></a>

This is the repository of EpicTunes, a music streaming platform that lets you upload your best freestyles and stream those of others.

Made as a high school project back in 2022-23. The main premise of the app is that users can upload their own songs, the same way it works on Soundcloud. More details and a Ruby on Rails-based feature-packed version is [available in this repository](https://github.com/tzuntar/rubytunes).

## 📝 Table of Contents

- [Getting Started](#getting_started)
- [Deployment](#deployment)
- [Authors](#authors)
- [Acknowledgments](#acknowledgement)

## 🏁 Getting Started <a name = "getting_started"></a>

These instructions will get you a copy of the EpicTunes up and running on your local machine for development and testing
purposes. See [deployment](#deployment) for notes on how to deploy the project on a live system.

### ✅ Prerequisites <a name = "prerequisites"></a>

- PHP >= 7.4
- Composer
- A working MySQL instance

### ⏳Installing

1. Get the MySQL instance up & running.
2. Import the database from `create_db.sql`.
3. Edit the connection details in [include/database.php](include/database.php) to match those of your MySQL database
   instance.
4. Run `composer install` to install all required dependencies.
5. Run your web server, register yourself, and you're good to go.

## 🚀 Deployment <a name = "deployment"></a>

Follow the steps in the **Installing** section but also set the `$toplevel` variable
in [include/database.php](include/database.php) to match your domain name.

## ✍️ Authors <a name = "authors"></a>

- [@tzuntar](https://github.com/tzuntar) - Development

## 🎉 Acknowledgements <a name = "acknowledgement"></a>

This project was inspired by SoundCloud and Spotify.
