import React, {Component} from 'react';
import axios from 'axios';
import MuiThemeProvider from 'material-ui/styles/MuiThemeProvider';
import AppBar from 'material-ui/AppBar';
import RaisedButton from 'material-ui/RaisedButton';
import TextField from 'material-ui/TextField';

const apiBaseUrl = "http://quizbank.com/api/";

class Question extends Component {
    constructor(props) {
        super(props);
        this.state = {
            question: '',
            answer1: '',
            answer2: '',
            answer3: '',
            answer4: ''
        }

        this.handleButtonClick = this.handleButtonClick.bind(this);
    }

    handleButtonClick() {
        const self = this;
        const payload = {
            "question":this.state.question,
            "answer1":this.state.answer1,
            "answer2":this.state.answer2,
            "answer3":this.state.answer3,
            "answer4":this.state.answer4,
        }

        axios.post(apiBaseUrl+'addQuestion', payload)
            .then(function (response) {
                console.log(response);
                if (response.status === 200) {
                    const successMessage = "Question added successfully!";
                    self.props.parentContext.setState (
                        {
                            page  : [],
                            title : '',
                            message : successMessage
                        }
                    );
                } else {
                    console.log("Error in adding question");
                    alert("Error in adding question");
                }
            })
            .catch(function (error) {
                console.log(error);
            });
    }

    render() {
        return (
            <div>
                <MuiThemeProvider>
                    <div>
                        <AppBar title="Question" showMenuIconButton={false} />
                        <TextField
                            hintText="Enter your question"
                            floatingLabelText="Question"
                            onChange = {(event,newValue) => this.setState({question:newValue})}
                        />
                        <br/>
                        <TextField
                            hintText="Enter your answer"
                            floatingLabelText="Answer1"
                            onChange = {(event,newValue) => this.setState({answer1:newValue})}
                        />
                        <br/>
                        <TextField
                            hintText="Enter your answer"
                            floatingLabelText="Answer2"
                            onChange = {(event,newValue) => this.setState({answer2:newValue})}
                        />
                        <br/>
                        <TextField
                            hintText="Enter your answer"
                            floatingLabelText="Answer3"
                            onChange = {(event,newValue) => this.setState({answer3:newValue})}
                        />
                        <br/>
                        <TextField
                            hintText="Enter your answer"
                            floatingLabelText="Answer4"
                            onChange = {(event,newValue) => this.setState({answer4:newValue})}
                        />
                        <br/>
                        <div className="button-div">
                            <RaisedButton className="button" label="Submit" primary={true} onClick={this.handleButtonClick}/>
                        </div>
                    </div>
                </MuiThemeProvider>
            </div>
        );
    }
}
export default Question;