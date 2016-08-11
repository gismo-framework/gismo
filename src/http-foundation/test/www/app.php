<?php
    /**
     * Created by PhpStorm.
     * User: matthes
     * Date: 14.08.16
     * Time: 12:56
     */


    namespace Gismo\Test;

    use Gismo\Component\HttpFoundation\Request\RequestFactory;
    use Gismo\Test\Component\HttpFoundation\RequestFactoryTest;

    require __DIR__ . "/../../vendor/autoload.php";


    highlight_string(print_r ($_SERVER, true));


    $req = RequestFactory::BuildFromEnv();
    highlight_string(print_r ($req, true));

    ?>
<a href="<?php echo $req->URL . "/" . urlencode("some/sub/p?'ath"); ?>">Test subpath parsing (single urlencode) - should fail</a>
<br><a href="<?php echo $req->URL . "/" . urlencode(urlencode("some/sub/p?'ath")); ?>">Test subpath parsing (double urlencode)</a>
