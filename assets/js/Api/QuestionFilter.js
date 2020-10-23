import React, {Component} from 'react';
import axios from 'axios';

const apiBaseUrl = 'http://quizbank.com/api/';
const QuestionFilter = {
    getQuestionCategories() {
        axios.get(apiBaseUrl+'getQuestionCategories')
            .then((response) => {
                if (response.status === 200) {
                    return response.data.questionCategories;
                }
            });
    }
}

export default QuestionFilter;