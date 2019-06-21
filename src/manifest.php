<?php
namespace pdima88\icms2bonuscode;

class manifest {
    function hooks() {
        return [];
    }

    function getRootPath() {
        return realpath(dirname(__FILE__).'/..');
    }
}

