import React from 'react';
import { Wrapper, ButtonWrapper } from './QuestionCard.styles';
import Timer from './Timer';

const QuestionCard = ({
                       question,
                       answers,
                       callback,
                       userAnswer,
                       questionNr,
                       totalQuestions,
                       time,
                   }) => (
        <Wrapper>
            <Timer time={time} />
            <p className='number'>
                Question: {questionNr} / {totalQuestions}
            </p>
            <p dangerouslySetInnerHTML={{ __html: question }} />
            <div>
                {answers.map((answer) => (
                    <ButtonWrapper
                        key={answer}
                        correct={userAnswer?.correctAnswer === answer}
                        userClicked={userAnswer?.answer === answer}
                    >
                        <button disabled={!!userAnswer} value={answer} onClick={callback}>
                            <span dangerouslySetInnerHTML={{ __html: answer }} />
                        </button>
                    </ButtonWrapper>
                ))}
            </div>
        </Wrapper>
);

export default QuestionCard;
