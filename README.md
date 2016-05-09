# Jekxyl

Jekxyl is a static website generator written with the XYL language.

## Installation

With [Composer](http://getcomposer.org/), you need to require
[`jekxyl/jekxyl`](https://packagist.org/packages/jekxyl/jekxyl):

```sh
$ composer global require jekxyl/jekxyl
```

This command will install —by default— Jekxyl in your home. Once the
`bin/jekxyl` script in the `PATH`, we will be able to run:

```sh
$ jekxyl
```

### Basic usage

First of all, to ease the start of the Web application, one will use the `jekxyl
new` command, which expects a directory name to be provided:

```sh
$ jekxyl new MyProject
```

Now we are going to see how to compile these files into a static Web
application, with the help of the `jekxyl compile` command, which expects a
source and a destination (respectively the `Source` and the `Dist` directories in
this particular example):

```sh
$ jekxyl compile --source MyProject/Source --destination MyProject/Destination
```

*Et voilà*, a nice `MyProject/Dist/index.html` has been created. Because all
links are absolute, to watch the Web application with all the comfort, one might
use the `jekxyl serve` command to start an HTTP server. This command expects at
least a root (default is `.`):

```sh
$ jekxyl serve --root MyProject/Dist/
Server is listening MyProject/Dist on 127.0.0.1:8888
```

Thus, open `127.0.0.1:8888` in your favorite browser and see your first Web
application with Jekxyl! 
