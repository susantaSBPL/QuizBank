import {shuffleArray} from '../utils';

export const fetchQuizQuestions = async (category, type, difficulty, number)  => {
    const endpoint = `https://opentdb.com/api.php?amount=${number}&difficulty=${difficulty}&type=${type}`;
    const data = await (await fetch(endpoint)).json();
    return data.results.map((question) => ({
        ...question,
        answers: shuffleArray([...question.incorrect_answers, question.correct_answer]),
    }))
};
