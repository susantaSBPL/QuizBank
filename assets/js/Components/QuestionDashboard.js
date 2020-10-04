import React, {Component} from 'react';
import QuestionType from "./QuestionType";
import QuestionCategory from "./QuestionCategory";
import Question from "./Question";

class QuizDashboard extends Component {
    constructor(props) {
        super(props);
        this.state = {
            page: [],
        }

        this.handleButtonClick = this.handleButtonClick.bind(this);
    }

    handleButtonClick(e) {
        switch (e.target.id) {
            case 'question-type':
                this.setState({
                    page: [<QuestionType key={'type'} parentContext={this} appContext={this.props.parentContext} />]
                });
                break;
            case 'question-category':
                this.setState({
                    page: [<QuestionCategory key={'category'} parentContext={this} appContext={this.props.parentContext} />]
                });
                break;
            case 'question':
                this.setState({
                    page: [<Question key={'question'} parentContext={this} appContext={this.props.parentContext} />]
                });
                break;
        }
    }

    render() {
        return (
            <section>
                <div className="wrapper mt-2">
                    <div className="row col-12">
                        <div className="col">
                            <button id="question" className='start col' onClick={this.handleButtonClick}>
                                Question
                            </button>
                        </div>
                        <div className="col">
                            <button id="question-type" className='start col' onClick={this.handleButtonClick}>
                                Question Type
                            </button>
                        </div>
                        <div className="col">
                            <button id="question-category" className='start col' onClick={this.handleButtonClick}>
                                Question Category
                            </button>
                        </div>
                    </div>
                    <div className="col-12 question-block">
                        {this.state.page}
                    </div>
                </div>
            </section>
        )
    }
}

export default QuizDashboard;