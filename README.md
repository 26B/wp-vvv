# WP Seed

This project aims to allow for a quick start in developing and deploying, of WordPress projects with a configuration for VVV 2.0.

## Introduction

This guide assumes some basic familiarity with the command line, the Git version control system and Vagrant.

There are many graphical tools allowing you to achieve the same results as the ones described here, but a step-by-step guide for those is outside the scope of this document.

## Before you begin

This setup requires recent versions of both Vagrant and VirtualBox to be installed.

[Vagrant](http://www.vagrantup.com) is a "tool for building and distributing development environments". It works with [virtualization](http://en.wikipedia.org/wiki/X86_virtualization) software such as [VirtualBox](https://www.virtualbox.org/) to provide a virtual machine sandboxed from your local environment.

## Adding the Project to Your VVV Environment

This project should be added to the `www` directory in your VVV setup.  If you've followed the guide to installing VVV, the directory may be reached by typing `cd vagrant-local/www` in the command line interface.

Now clone the project into the `www` folder:

```
$ git clone git@github.com:WP-Seed/wp-seed.git wp-seed
```

This should create a new directory called `wp-seed` containing all the project files.

Add the site to your `vvv-custom.yml` (if you don't know what this is, check out [the documentation](https://varyingvagrantvagrants.org/docs/en-US/adding-a-new-site/)):

```
wp-seed:
  hosts:
    - wp-seed.dev
```

We're not done yet: VVV needs to setup and incorporate the project into its configuration. That is achieved by running:

```
$ vagrant provision --provision-with site-wp-seed
```

### Further configuration

You can configure your site right from the YAML block you just entered, just use the `custom:` key to set your particular settings. For example, let's say you want a different database name (other than the default that is created using the site name):

```
wp-seed:
  hosts:
    - wp-seed.dev
  custom:
    db_name: special_db_name
```
That's all you need!

The following table specifies all the variables you can set in `vvv-custom.yml` and their respective defaults.

| Variable       | Description                | Possible Values                       | Default                       |
| -------------- | -------------------------- | ------------------------------------- | ----------------------------- |
| `site_title`   | The site's title           | Any string                            | Primary domain                |
| `wp_type`      | Type of installation       | `single`, `subdirectory`, `subdomain` | `single`                      |
| `db_name`      | Database name              | Valid MySQL database name             | `[escaped_vvv_site_name]_dev` |
| `db_user`      | Database user              |                                       | `wp`                          |
| `db_password`  | Database password for user |                                       | `wp`                          |
| `db_host`      | Database hostname          | Hostname                              | `localhost`                   |
| `db_charset`   | Database charset           | [MySQL Charset Sets and Collation][1] | `utf8mb4`                     |
| `db_collate`   | Database collation         | [MySQL Charset Sets and Collation][1] | `utf8mb4_general_ci`          |
| `table_prefix` | Database table prefix      |                                       | `wp_`                         |
| `wp_plugins`   | The plugins to activate    |                                       |                               |
| `wp_theme`     | The theme to activate      |                                       |                               |

[1]: https://dev.mysql.com/doc/refman/5.7/en/charset-charsets.html

## What now?

This project is meant to be all about giving you a head start in setting up the WordPress environment for development. It also provides several helpful scripts and tries to make good choices in what is setup for you.

### There's no theme dude!

You're darn right! There is no themes folder at all, so you are free to plug in any theme base/starter you'd like.

If I may offer a suggestion it would be to use the Genesis Framework and start with this starter child-theme: [Genesis Starter](https://github.com/goblindegook/genesis-starter). However, any selection you make will surely be wise.
