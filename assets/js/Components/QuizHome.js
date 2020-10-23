import React, {useState} from 'react';
import Select from 'react-select';
import AsyncSelect from 'react-select/async';
import axios from 'axios';
import {fetchQuizQuestions} from '../Api/Questions';
import {getQuestionRequirements} from '../Api/Questions';
import QuestionCard from './QuestionCard';
import QuestionFilter from "../Api/QuestionFilter";

const apiBaseUrl = 'http://quizbank.com/api/';
const categoryOptions = [];
// [
//     { value: '9', label: 'General Knowledge' },
//     { value: '11', label: 'Entertainment' },
//     { value: '21', label: 'Sports' },
//     { value: '23', label: 'History' }
// ];

const typeOptions = [];
// [
//     { value: 'multiple', label: 'Multiple Choice' },
//     { value: 'boolean', label: 'True False' }
// ];

const difficultyOptions = [
    { value: 'easy', label: 'EASY' },
    { value: 'medium', label: 'MEDIUM' },
    { value: 'hard', label: 'HARD' }
];

const questionNumber = [
    { value: '10', label: '10' },
    { value: '25', label: '25' },
    { value: '50', label: '50' },
    { value: '100', label: '100' }
];

const ANSWER_TIME = 30;

const QuizHome = () => {
    // const [categories, setCategories]     = useState([]);
    // const [types, setTypes]               = useState([]);
    const [category, setCategory]         = useState('');
    const [type, setType]                 = useState('');
    const [difficulty, setDifficulty]     = useState('');
    const [number, setNumber]             = useState(0);
    const [totalNumber, setTotalNumber]   = useState(10);
    const [loading, setLoading]           = useState(false);
    const [questions, setQuestions]       = useState([]);
    const [userAnswers, setUserAnswers]   = useState([]);
    const [score, setScore]               = useState(0);
    const [gameOver, setGameOver]         = useState(true);
    const [time, setTime]                 = useState(ANSWER_TIME);
    const [disableClass, setDisableClass] = useState('');

    const startTrivia = async () => {
        setLoading(true);
        setGameOver(false);
        setTime(ANSWER_TIME*totalNumber);
        const newQuestions = await fetchQuizQuestions(
            category,
            type,
            difficulty,
            totalNumber
        );
        setQuestions(newQuestions);
        setScore(0);
        setUserAnswers([]);
        setNumber(0);
        setLoading(false);
        setDisableClass('disabled-content');
    };

    const getCategories = (input) => {
        const categories = QuestionFilter.getQuestionCategories();
        console.log(QuestionFilter.getQuestionCategories());

        return categories;
    };

    const checkAnswer = (e) => {
        if (!gameOver) {
            const answer  = e.currentTarget.value;
            const correct = questions[number].correct_answer === answer;

            if (correct) setScore((prev) => prev + 1);

            const answerObject = {
                question: questions[number].question,
                answer,
                correct,
                correctAnswer: questions[number].correct_answer,
            };
            setUserAnswers((prev) => [...prev, answerObject]);
        }
    };

    const nextQuestion = () => {
        // Move on to the next question if not the last question
        const nextQ = number + 1;

        if (nextQ === number) {
            setGameOver(true);
        } else {
            setNumber(nextQ);
        }
    };

    const completeQuiz = () => {
        setCategory('');
        setType('');
        setDifficulty('');
        setNumber(0);
        setTotalNumber(10);
        setLoading(false);
        setQuestions([]);
        setUserAnswers([]);
        setScore(0);
        setGameOver(true);
        setTime(ANSWER_TIME);
        setDisableClass('');
    }

    const handleQuestionCategoryChange = (option) => {
        setCategory(option.value);
    }

    const handleQuestionTypeChange = (option) => {
        setType(option.value);
    }

    const handleQuestionDifficultyChange = (option) => {
        setDifficulty(option.value);
    }

    const handleQuestionNumberChange = (option) => {
        setTotalNumber(option.value);
    }

    return (
        <section className={"container quiz-home mt-2"}>
            <div className="row col-12">
                <div className={"col-3 border-right-primary "+disableClass}>
                    <div className="row mt-2">
                        <label htmlFor="quiz-category" className={"col-12"}><b>Question Category</b></label>
                        <AsyncSelect
                            className='quiz-category col-12'
                            onChange={handleQuestionCategoryChange}
                            loadOptions={getCategories}
                            defaultOptions
                        />
                    </div>
                    <div className="row mt-1">
                        <label htmlFor="quiz-type" className={"col-12"}><b>Type</b></label>
                        <Select className='quiz-type col-12' onChange={handleQuestionTypeChange} options={typeOptions}/>
                    </div>
                    <div className="row mt-1">
                        <label htmlFor="quiz-difficulty" className={"col-12"}><b>Difficulty</b></label>
                        <Select className='quiz-difficulty col-12' onChange={handleQuestionDifficultyChange} options={difficultyOptions}/>
                    </div>
                    <div className="row mt-1">
                        <label htmlFor="question-count" className={"col-12"}><b>No of Questions</b></label>
                        <Select className='question-count col-12' onChange={handleQuestionNumberChange} options={questionNumber}/>
                    </div>
                    <div className="row mt-1">
                        <button className='start ml-3' onClick={startTrivia}>
                            Start
                        </button>
                    </div>
                </div>
                <div className={"col-8 text-center"}>
                    {gameOver || userAnswers.length === totalNumber ? (
                        <div className={"mt-2"}>
                            <h2 className={"text-center"}>Remember</h2><hr />
                            <p>Please select filters to load Quiz. <br />Each question has 30 secs and
                                Total time will be calculated by total questions selected.</p>
                        </div>
                    ) : null}
                    {!gameOver ? <p className='score'>Score: {score}</p> : null}

                    {loading ? <p>Loading Questions...</p> : null}

                    {!loading && !gameOver && (
                        <QuestionCard
                            question={questions[number].question}
                            answers={questions[number].answers}
                            callback={checkAnswer}
                            userAnswer={userAnswers ? userAnswers[number] : ''}
                            questionNr={number + 1}
                            totalQuestions={totalNumber}
                            time={time}
                        />
                    )}

                    {!gameOver && !loading && userAnswers.length === number + 1 && number !== totalNumber - 1 ? (
                        <button className='next' onClick={nextQuestion}>
                            Next Question
                        </button>
                    ) : (userAnswers.length === number + 1 && number === totalNumber - 1 ? (
                            <button className='next' onClick={completeQuiz}>
                                Completed, Start Again
                            </button>
                        ) : null )}
                </div>
            </div>
        </section>
    )
}

export default QuizHome;