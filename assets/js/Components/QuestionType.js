import React, {Component} from 'react';
import axios from 'axios';
import MuiThemeProvider from 'material-ui/styles/MuiThemeProvider';
import AppBar from 'material-ui/AppBar';
import RaisedButton from 'material-ui/RaisedButton';
import TextField from 'material-ui/TextField';
import SweetAlert from 'react-bootstrap-sweetalert';

const apiBaseUrl = "http://quizbank.com/api/";

class QuestionType extends Component {
    constructor(props){
        super(props);
        this.state = {
            typeName:'',
            alert: [],
            message: ''
        }

        this.handleButtonClick = this.handleButtonClick.bind(this);
        this.handleSweetAlert  = this.handleSweetAlert.bind(this);
    }

    componentWillMount() {
        this.setState({
            typeName:'',
            alert: [],
            message: ''
        });
    }

    handleSweetAlert() {
        this.setState({
            typeName:'',
            alert: [],
            message: ''
        });
    }

    handleButtonClick() {
        const self = this;
        const payload = {
            "type":this.state.typeName
        }

        axios.post(apiBaseUrl+'addQuestionType', payload)
            .then(function (response) {
                if (response.status === 200) {
                    const successMessage = "Question Type added successfully!";
                    self.props.parentContext.setState (
                        {
                            page  : [],
                            title : '',
                            message : successMessage
                        }
                    );
                } else {
                    self.setState({
                        showAlert: true,
                        message: "Error in adding question type"
                    });
                }
            })
            .catch(function (error) {
                self.setState({
                    showAlert: true,
                    message: error
                });
            });
    }

    render() {
        return (
            <div>
                <MuiThemeProvider>
                    <div>
                        <AppBar title="Question Type" showMenuIconButton={false} />
                        <TextField
                            hintText="Enter question type name"
                            floatingLabelText="Type name"
                            onChange = {(event,newValue) => this.setState({typeName:newValue})}
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
export default QuestionType;