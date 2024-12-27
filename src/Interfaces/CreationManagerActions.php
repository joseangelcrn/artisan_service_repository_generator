<?php

namespace josanangel\ServiceRepositoryManager\Interfaces;

interface CreationManagerActions
{

    function normalizeClassName();
    function generateFile();
    function generateConstructor();
    function resolveVariables();
    function addAttributeToClass($varName,$varType);
    function addParamToConstructor($varName,$varType);

    function applySuffix();
}

