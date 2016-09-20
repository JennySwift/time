var page = require('webpage').create();

var url = 'http://time.dev:8000/login';
var email = 'cheezyspaghetti@gmail.com';
var password = 'abcdefg';

casper.test.begin('It works', function suite(test) {
    casper.start(url, function() {
        this.echo("I'm loaded.");
    });

    casper.viewport(1024, 768);

    casper.waitForSelector("form input[name='email']", function() {
        this.fillSelectors('form', {
            'input[name = email ]' : email,
            'input[name = password ]' : password
        }, true);
        this.capture('test-img/logged-in.png');
    });

    casper.waitForSelector('#something', function () {
        casper.waitForText('some text', function () {
            this.echo('Some text is on the page');
            casper.wait(100, function () {
                this.capture('test-img/img.png');
                test.assertVisible('.table');
                this.echo('The table is visible');
            });
        });
    });

    // casper.waitUntilVisible('#something', function () {
    //     this.echo('something is visible');
    //     casper.wait(100, function () {
    //         test.assertTextExists('something');
    //     });
    // });

    casper.run(function() {
        test.done();
        this.echo('The suite ended.');
    });
});































