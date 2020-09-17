import React from 'react';
import {useState} from 'react';
import Select from 'react-select';
import {fetchQuizQuestions} from '../Api/Questions';
import QuestionCard from './QuestionCard';

const categoryOptions = [
    { value: '9', label: 'General Knowledge' },
    { value: '11', label: 'Entertainment' },
    { value: '21', label: 'Sports' },
    { value: '23', label: 'History' }
];

const typeOptions = [
    { value: 'multiple', label: 'Multiple Choice' },
    { value: 'boolean', label: 'True False' }
];

const questionNumber = [
    { value: '10', label: '10' },
    { value: '25', label: '25' },
    { value: '50', label: '50' },
    { value: '100', label: '100' }
];

const ANSWER_TIME = 10000;

const QuizHome = () => {
    const [category, setCategory]       = useState('');
    const [type, setType]               = useState('');
    const [number, setNumber]           = useState(0);
    const [totalNumber, setTotalNumber] = useState(10);
    const [loading, setLoading]         = useState(false);
    const [questions, setQuestions]     = useState([]);
    const [userAnswers, setUserAnswers] = useState([]);
    const [score, setScore]             = useState(0);
    const [gameOver, setGameOver]       = useState(true);
    const [time, setTime]               = useState(ANSWER_TIME);

    const startTrivia = async () => {
        setLoading(true);
        setGameOver(false);
        const newQuestions = await fetchQuizQuestions(
            category,
            type,
            totalNumber
        );
        setQuestions(newQuestions);
        setScore(0);
        setUserAnswers([]);
        setNumber(0);
        setLoading(false);
        setTime(ANSWER_TIME);
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
            setTime(ANSWER_TIME);
        }
    };

    const handleQuestionCategoryChange = (option) => {
        setCategory(option.value);
    }

    const handleQuestionTypeChange = (option) => {
        setType(option.value);
    }

    const handleQuestionNumberChange = (option) => {
        setTotalNumber(option.value);
    }

    return (
        <section>

            <div className="wrapper mt-2">
                {gameOver || userAnswers.length === totalNumber ? (
                    <div className="row col-12">
                        <div className="col-3">
                            <label htmlFor="quiz-category"><b>Question Category</b></label>
                            <Select className='quiz-category' onChange={handleQuestionCategoryChange} options={categoryOptions}/>
                        </div>
                        <div className="col-3">
                            <label htmlFor="quiz-type"><b>Type</b></label>
                            <Select className='quiz-type' onChange={handleQuestionTypeChange} options={typeOptions}/>
                        </div>
                        <div className="col-3">
                            <label htmlFor="question-count"><b>No of Questions</b></label>
                            <Select className='question-count' onChange={handleQuestionNumberChange} options={questionNumber}/>
                        </div>
                        <div className="col-3">
                            <button className='start col' onClick={startTrivia}>
                                Start
                            </button>
                        </div>
                    </div>
                ) : null }

                {!gameOver ? <p className='score'>Score: {score}</p> : null}

                {loading ? <p>Loading Questions...</p> : null}

                {/*{!loading && !gameOver && (*/}
                {/*    <Timer*/}
                {/*        initialTime={time}*/}
                {/*        direction="backward"*/}
                {/*    >*/}
                {/*        {() => (*/}
                {/*            <React.Fragment>*/}
                {/*                <div className="timer">*/}
                {/*                    <Timer.Minutes /> : <Timer.Seconds />*/}
                {/*                </div>*/}
                {/*            </React.Fragment>*/}
                {/*        )}*/}
                {/*    </Timer>*/}
                {/*)}*/}

                {!loading && !gameOver && (
                    <QuestionCard
                        question={questions[number].question}
                        answers={questions[number].answers}
                        callback={checkAnswer}
                        userAnswer={userAnswers ? userAnswers[number] : ''}
                        questionNr={number + 1}
                        totalQuestions={totalNumber}
                        time={time}
                        questionCallback={nextQuestion}
                    />
                )}

                {!gameOver && !loading && userAnswers.length === number + 1 && number !== totalNumber - 1 ? (
                    <button className='next' onClick={nextQuestion}>
                        Next Question
                    </button>
                ) : null}
            </div>
        </section>
    )
}

export default QuizHome;