Namespace / Class structure
---------------------------
Namespace/Class structure follows [PSR-4](http://www.php-fig.org/psr/psr-4/). Layers (descibed below) live under
their own namespace prefixes. Top-level namespace (vendor prefix) is Weirdan.

Prefixes:
| Layer     | Namespace prefix       | Directory |
|-----------|------------------------|-----------|
| Network   | Weirdan\Xdebug\Network | Network   |
| Core      | Weirdan\Xdebug\Core    | Core      |
| Interface | Weirdan\Xdebug\View    | View      |

Layering
--------
Debugger application is composed of 3 layers:
    1. Interface layer (cli and gui frontend)
    2. Core layer  
    3. Network layer

### Network layer
Network layer should be fully compatible with PHP (thus no Phalanger-specific constructs should be used).
In Phalanger context, it's intended to be run in a separate thread. It's stateless, as far as the application
is concerned (meaning it does not store anything useful for application, like the list of breakpoints) but it 
may have it's own state (like sequence counter for transaction id generation or the list of outstanding requests).
This state should not be exposed outside.

### Core layer
Core layer retains all the application state. List of breakpoints, source files, etc. It facilitates communication 
between interface layer and network layer.

### Interface layer
Two frontends planned: GTK# and CLI (both in Phalanger initially).

Threading
---------
Network layer runs in it's own thread. It uses synchronous network IO to simplify things, and communicates with
other layers by calling implicitly synchronous Core layer methods (passed to it as callbacks).

Core and interface layers may be run in the same thread or in their separate threads, depending on the possibilities
offered by GTK# and Phalanger. This is still to be decided.
