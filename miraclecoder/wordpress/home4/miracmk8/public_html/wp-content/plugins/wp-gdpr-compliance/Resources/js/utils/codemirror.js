import CodeMirror from 'codemirror'

/**
 * Admin Component for textarea with code
 */

export default class ExtendCodeMirror {
    constructor () {
        this.setProperties()
        this.init()
    }

    init () {
        this.createCodeMirror()
    }

    setProperties () {
        this.fields = document.querySelectorAll('.wpgdprc-codemirror')
        this.mirrors = []
    }

    createCodeMirror () {
        if (!this.fields) {
            return
        }

        this.fields.forEach(field => {
            this.mirrors.push(CodeMirror.fromTextArea(field,
                {
                    mode: 'text/html',
                    lineNumbers: true,
                    matchBrackets: true,
                    tabSize: 2,
                    indentUnit: 2
                }
            ))
        })
    }

    refreshMirrors () {
        if (this.mirrors.length < 1) {
            return
        }

        this.mirrors.forEach(mirror => mirror.refresh())
    }

    saveMirrors () {
        if (this.mirrors.length < 1) {
            return
        }

        this.mirrors.forEach(mirror => mirror.save())
    }
}
