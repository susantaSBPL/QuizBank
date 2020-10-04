import React, {Component} from 'react';
import axios from 'axios';
import MuiThemeProvider from 'material-ui/styles/MuiThemeProvider';
import AppBar from 'material-ui/AppBar';
import RaisedButton from 'material-ui/RaisedButton';
import TextField from 'material-ui/TextField';
import FlashMessage from "react-flash-message";
import {Table} from "react-bootstrap";

const apiBaseUrl = "http://quizbank.com/api/";

class QuestionType extends Component {
    constructor(props){
        super(props);
        this.state = {
            typeName:'',
            message: '',
            showAlert: false,
            alertClass: '',
            availableTypes: [],
        }

        this.header = [
            {title: "ID", prop: "id", sortable: true, filterable: true},
            {title: "Type", prop: "type", sortable: true, filterable: true},
        ]

        this.handleButtonClick  = this.handleButtonClick.bind(this);
    }

    componentDidMount() {
        axios.get(apiBaseUrl+'getQuestionTypes')
            .then((response) => {
                if (response.status === 200) {
                    let questionTypes = [];
                    response.data.questionTypes.map(value => {
                        questionTypes.push(value);
                    });
                    this.setState({
                        availableTypes: questionTypes
                    })
                }
            });
    }

    handleButtonClick() {
        const self = this;
        let availableTypes = this.state.availableTypes;
        const payload = {
            "type":this.state.typeName
        }

        axios.post(apiBaseUrl+'addQuestionType', payload)
            .then(function (response) {
                if (response.status === 200) {
                    const successMessage = "Question Type added successfully!";
                    self.props.parentContext.setState (
                        {
                            page  : [<QuestionType key={'type'} parentContext={self.props.parentContext} appContext={self.props.appContext} />],
                        }
                    );
                    availableTypes.push(response.data.questionType);
                    self.setState({
                        showAlert: true,
                        alertClass: 'alert alert-success',
                        message: successMessage,
                        availableTypes: availableTypes,
                        typeName: ''
                    });
                } else {
                    self.setState({
                        showAlert: true,
                        alertClass: 'alert alert-error',
                        message: "Error in adding question type"
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
            <div className={"col-12 row"}>
                <section className="col-6 border-right">
                    <Table striped bordered hover>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Type</th>
                            </tr>
                        </thead>
                        <tbody>
                            {this.state.availableTypes.map((typeObj, index) => {
                                return (
                                    <tr className={"row-even-odd"} key={index}>
                                        <td>{typeObj.id}</td>
                                        <td>{typeObj.type}</td>
                                    </tr>
                                )
                            })}
                        </tbody>
                    </Table>
                </section>
                <section className="col-6">
                    {this.state.message ? (
                        <FlashMessage duration={5000}>
                            <div className={this.state.alertClass} role="alert">{this.state.message}</div>
                        </FlashMessage>
                    ) : null}
                    <MuiThemeProvider key={2}>
                        <div>
                            <AppBar title="Add Question Type" showMenuIconButton={false} />
                            <TextField
                                hintText="Enter question type name"
                                value={this.state.typeName}
                                floatingLabelText="Type name"
                                onChange = {(event,newValue) => this.setState({typeName:newValue})}
                            />
                            <br/>
                            <div className="button-div">
                                <RaisedButton className="button" label="Submit" primary={true} onClick={this.handleButtonClick}/>
                            </div>
                        </div>
                    </MuiThemeProvider>
                </section>
            </div>
        );
    }
}
export default QuestionType;