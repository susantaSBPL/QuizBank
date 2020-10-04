import React, {Component} from 'react';
import axios from 'axios';

const apiBaseUrl = "http://quizbank.com/api/";

const ActivateUser = (props) => {
    const { match: { params } } = props;
    axios.post(apiBaseUrl+'activateUser/'+params.verificationKey)
        .then((response) => {
            if (response.status === 200) {
                window.location = '/';
            }
        });
}

export default ActivateUser;