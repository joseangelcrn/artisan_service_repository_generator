<?php

/**
 * Interface which group all common actions of CreationManager abstract class
 */

namespace josanangel\ServiceRepositoryManager\Interfaces;

interface CreationManagerActions
{

    function normalizeClassName();
    function generateFile();
    function generateConstructor();
    function generateNameSpace();
    function resolveVariables();
    function addAttributeToClass($varName,$varType);
    function addParamToConstructor($varName,$varType);

    function applySuffix();

    function run();
}

