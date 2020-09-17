import React from 'react';
import { Wrapper, ButtonWrapper } from './QuestionCard.styles';
// import Timer from 'react-compound-timer';

const QuestionCard = ({
                       question,
                       answers,
                       callback,
                       userAnswer,
                       questionNr,
                       totalQuestions,
                       time,
                       questionCallback
                   }) => (
        <Wrapper>
            {/*<Timer*/}
            {/*    initialTime={time}*/}
            {/*    direction="backward"*/}
            {/*    checkpoints={[*/}
            {/*        {*/}
            {/*            time: 0,*/}
            {/*            callback: () => questionCallback()*/}
            {/*        }*/}
            {/*    ]}*/}
            {/*>*/}
            {/*    {() => (*/}
            {/*        <React.Fragment>*/}
            {/*            <div className="timer">*/}
            {/*                <Timer.Minutes /> : <Timer.Seconds />*/}
            {/*            </div>*/}
            {/*        </React.Fragment>*/}
            {/*    )}*/}
            {/*</Timer>*/}
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
