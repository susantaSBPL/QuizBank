<?php declare(strict_types=1);

/*
 * (c) Kinetxx Inc <admin@kinetxx.com>
 */
namespace App\Entity;

/**
 * Class WeblogAction
 */
class WeblogAction
{
    const CREATE_NEW_USER              = 'CREATE_NEW_USER';
    const REGISTER_NEW_USER            = 'REGISTER_NEW_USER';
    const CREATE_NEW_QUESTION_TYPE     = 'CREATE_NEW_QUESTION_TYPE';
    const CREATE_NEW_QUESTION_CATEGORY = 'CREATE_NEW_QUESTION_CATEGORY';
    const CREATE_NEW_QUESTION          = 'CREATE_NEW_QUESTION';
    const USER_CREATE_VERIFICATION_URL = 'USER_CREATE_VERIFICATION_URL';
    const USER_DELETE_VERIFICATION_URL = 'USER_DELETE_VERIFICATION_URL';
}
