import React, {Component} from 'react';
import axios from 'axios';
import MuiThemeProvider from 'material-ui/styles/MuiThemeProvider';
import AppBar from 'material-ui/AppBar';
import RaisedButton from 'material-ui/RaisedButton';
import TextField from 'material-ui/TextField';
import Radio from '@material-ui/core/Radio';
import RadioGroup from "@material-ui/core/RadioGroup";
import FormControl from '@material-ui/core/FormControl';
import FormLabel from '@material-ui/core/FormLabel';
import FormControlLabel from '@material-ui/core/FormControlLabel';
import Select from "react-select";
import FlashMessage from "react-flash-message";
import Swal from "sweetalert2";

const apiBaseUrl = "http://quizbank.com/api/";

const correctAnswer = [
    {value: 1, label: 'Answer1'},
    {value: 2, label: 'Answer2'},
    {value: 3, label: 'Answer3'},
    {value: 4, label: 'Answer4'},
];

const questionDifficulty = [
    {value: 'EASY', label: 'EASY'},
    {value: 'MEDIUM', label: 'MEDIUM'},
    {value: 'HARD', label: 'HARD'},
]

class Question extends Component {
    constructor(props) {
        super(props);
        this.state = {
            questionTypes: [],
            questionCategories: [],
            selectedType: 0,
            selectedCategory: 0,
            selectedDifficulty: '',
            selectedFile: '',
            showAlert: false,
            alertClass: '',
            question: '',
            answer1: '',
            answer2: '',
            answer3: '',
            answer4: '',
            correctAnswer: ''
        }

        this.handleButtonClick = this.handleButtonClick.bind(this);
        this.showFileUpload    = this.showFileUpload.bind(this);
        this.onChangeHandler   = this.onChangeHandler.bind(this);
    }

    componentDidMount() {
        axios.get(apiBaseUrl+'getQuestionRequirements')
            .then((response) => {
                if (response.status === 200) {
                    let questionTypes      = [];
                    let questionCategories = [];
                    response.data.questionRequirements.types.map(valueObj => {
                        questionTypes.push({value: valueObj.id, label: valueObj.type});
                    });
                    response.data.questionRequirements.categories.map(valueObj => {
                        questionCategories.push({value: valueObj.id, label: valueObj.category});
                    });

                    this.setState({
                        questionTypes: questionTypes,
                        questionCategories: questionCategories
                    })
                }
            });
    }

    showFileUpload() {
        Swal.fire({
            title: 'Submit uploaded excel file to add Questions',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Upload',
        }).then((result) => {
            if (result.isConfirmed) {
                const data = new FormData()
                data.append('file', this.state.selectedFile)
                axios.post(apiBaseUrl+'addQuestionFile', data)
                    .then(function (response) {
                        if (response.status === 200) {
                            Swal.fire(
                                'Success',
                                response.message,
                                'success'
                            )
                        }
                    })
                    .catch(function (error) {
                        Swal.fire(
                            'Success',
                            error,
                            'success'
                        )
                    });
            }
        })
    }

    onChangeHandler(event) {
        this.setState({
            selectedFile: event.target.files[0]
        });
        this.showFileUpload();
    }

    handleButtonClick() {
        const self = this;
        const payload = {
            "questionType": this.state.questionType,
            "questionCategory": this.state.questionCategory,
            "questionDifficulty": this.state.selectedDifficulty,
            "question": this.state.question,
            "answer1" : this.state.answer1,
            "answer2" : this.state.answer2,
            "answer3" : this.state.answer3,
            "answer4" : this.state.answer4,
            "correctAnswer": this.state.correctAnswer
        }

        axios.post(apiBaseUrl+'addQuestion', payload)
            .then(function (response) {
                if (response.status === 200) {
                    self.props.parentContext.setState (
                        {
                            page  : [<Question key={'question'} parentContext={self.props.parentContext} appContext={self.props.appContext} />],
                        }
                    );
                    self.setState({
                        showAlert: true,
                        alertClass: 'alert alert-success',
                        message: "Question added successfully!",
                        question: '',
                        answer1: '',
                        answer2: '',
                        answer3: '',
                        answer4: '',
                        correctAnswer: ''
                    });
                } else {
                    self.setState({
                        showAlert: true,
                        alertClass: 'alert alert-error',
                        message: "Error in adding question"
                    });
                }
            })
            .catch(function (error) {
                self.setState({
                    showAlert: true,
                    alertClass: 'alert alert-error',
                    message: error
                });
            });
    }

    render() {
        return (
            <div className={"row"}>
                <section className="col-12 pl-0 pr-0">
                    {this.state.message ? (
                        <FlashMessage duration={5000}>
                            <div className={this.state.alertClass} role="alert">{this.state.message}</div>
                        </FlashMessage>
                    ) : null}
                    <MuiThemeProvider>
                        <div className={"section-right"}>
                            <AppBar title="Add Question" showMenuIconButton={false} />
                            <div className={"col-12 row"}>
                                <div className={"col-4"}>
                                    <Select
                                        className='question-type mt-1'
                                        classNamePrefix={"question-type"}
                                        placeholder="Question Type"
                                        onChange={(newValue) => {this.setState({questionType:newValue.value})}}
                                        options={this.state.questionTypes}
                                    />
                                </div>
                                <div className={"col-4"}>
                                    <Select
                                        className='question-category mt-1'
                                        classNamePrefix={"question-category"}
                                        placeholder="Question Category"
                                        onChange={(newValue) => {this.setState({questionCategory:newValue.value})}}
                                        options={this.state.questionCategories}
                                    />
                                </div>
                                <div className={"col-4"}>
                                    <Select
                                        className='question-difficulty mt-1'
                                        classNamePrefix={"question-difficulty"}
                                        placeholder="Question Difficulty"
                                        onChange={(newValue) => {this.setState({selectedDifficulty:newValue.value})}}
                                        options={questionDifficulty}
                                    />
                                </div>
                                <div className={"col-12"}>
                                    <TextField
                                        hintText="Enter your question"
                                        floatingLabelText="Question"
                                        value={this.state.question}
                                        className="text-field"
                                        onChange = {(event,newValue) => this.setState({question:newValue})}
                                    />
                                </div>
                                <div className={"col-6"}>
                                    <TextField
                                        hintText="Enter your answer"
                                        floatingLabelText="Answer1"
                                        value={this.state.answer1}
                                        className="text-field"
                                        onChange = {(event,newValue) => this.setState({answer1:newValue})}
                                    />
                                </div>
                                <div className={"col-6"}>
                                    <TextField
                                        hintText="Enter your answer"
                                        floatingLabelText="Answer2"
                                        value={this.state.answer2}
                                        className="text-field"
                                        onChange = {(event,newValue) => this.setState({answer2:newValue})}
                                    />
                                </div>
                                <div className={"col-6"}>
                                    <TextField
                                        hintText="Enter your answer"
                                        floatingLabelText="Answer3"
                                        value={this.state.answer3}
                                        className="text-field"
                                        onChange = {(event,newValue) => this.setState({answer3:newValue})}
                                    />
                                </div>
                                <div className={"col-6"}>
                                    <TextField
                                        hintText="Enter your answer"
                                        floatingLabelText="Answer4"
                                        value={this.state.answer4}
                                        className="text-field"
                                        onChange = {(event,newValue) => this.setState({answer4:newValue})}
                                    />
                                </div>
                                <div className={"col-6 mt-1"}>
                                    <FormControl component="fieldset">
                                        <FormLabel component="legend"><strong>Correct Answer</strong></FormLabel>
                                        <RadioGroup
                                            aria-label="Correct Answer"
                                            name="correct-answer"
                                            value={this.state.correctAnswer}
                                            onChange={(event,newValue) => this.setState({correctAnswer:newValue})}
                                        >
                                            <FormControlLabel value="1" control={<Radio />} label="Answer1" />
                                            <FormControlLabel value="2" control={<Radio />} label="Answer2" />
                                            <FormControlLabel value="3" control={<Radio />} label="Answer3" />
                                            <FormControlLabel value="4" control={<Radio />} label="Answer4" />
                                        </RadioGroup>
                                    </FormControl>
                                </div>
                                <div className={"col-6"}>
                                    <div>
                                        {/*<a className={"upload-file-anchor"} onClick={this.showFileUpload}>Upload File</a>*/}
                                        <input type={"file"} onChange={this.onChangeHandler} />
                                    </div>
                                    <div className="button-div position-absolute top-40">
                                        <RaisedButton className="button" label="Submit" primary={true} onClick={this.handleButtonClick}/>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </MuiThemeProvider>
                </section>
            </div>
        );
    }
}
export default Question;