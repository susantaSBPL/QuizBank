import {shuffleArray} from '../utils';

export const fetchQuizQuestions = async (category, type, number)  => {
    const endpoint = `https://opentdb.com/api.php?amount=${number}&difficulty=medium&type=${type}`;
    const data = await (await fetch(endpoint)).json();
    return data.results.map((question) => ({
        ...question,
        answers: shuffleArray([...question.incorrect_answers, question.correct_answer]),
    }))
};
