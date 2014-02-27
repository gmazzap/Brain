<?php
namespace Brain;

interface Module {

    function boot( Container $brain );

    function getBindings( Container $brain );
}