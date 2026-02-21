import { Form, InputGroup } from "react-bootstrap";
import Button from 'react-bootstrap/Button';
import { Search as SearchIcon } from "react-bootstrap-icons";

export function Search({ isSearching, inputSearch, handleSubmit }) {
    return (
        <Form onSubmit={handleSubmit} >
            <Form.Group className="mb-3" controlId="exampleForm.ControlInput1">
                <InputGroup className="mb-3">
                    <Form.Control
                        ref={inputSearch}
                        disabled={isSearching}
                        placeholder="ระบุเขต/อำเภอ default: Bang Sue"
                    />
                    <Button
                        variant="primary"
                        id="button-addon2"
                        type="submit"
                        disabled={isSearching}
                    >
                        <SearchIcon />ค้นหา
                    </Button>
                </InputGroup>
            </Form.Group>
        </Form>
    )
}