/**
 * Created by Muhammad-ASUS on 8/10/2018.
 */


var express         = require('express');
var bodyParser      = require('body-parser');
var mongoos         = require('mongoose');
var language_models = require('../controllers/language');
var auth_models 	= require('../controllers/auth');

var router = express.Router();
var url_encode = bodyParser.urlencoded({ extended: false});
var json_parser = bodyParser.json();

router.get("/", url_encode, function (req, res) {

    console.log( 'empty page console' );
    res.send('empty page');
});


router.post("/language", url_encode, function (req, res) {

    res.setHeader('Access-Control-Allow-Origin', '*');
    res.setHeader('Access-Control-Allow-Headers', 'Content-Type');

    language_models.language( function (lang) {
        res.send( lang );
    });

});

router.post("/auth", url_encode, function (req, res) {

    res.setHeader('Access-Control-Allow-Origin', '*');
    res.setHeader('Access-Control-Allow-Headers', 'Content-Type');

    auth_models.auth( req.body, function (auth) {
        res.send( auth );
    });

});


module.exports = router;
